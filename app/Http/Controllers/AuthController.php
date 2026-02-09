<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Mail\PasswordReset;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Rules\ValidImageType;
use App\Models\ForgetPassword;
use App\Trait\FileHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Str;

class AuthController extends Controller
{
    public $fileHandler;

    public function __construct(FileHandler $fileHandler)
    {
        $this->fileHandler = $fileHandler;
    }

    public function login(Request $request)
    {
        // 1. Detect if we are on a Subdomain or Main Domain
        $host = $request->getHost();
        $parts = explode('.', $host);
        
        $isSubdomain = false;
        $subdomain = null;

        if ($host !== 'localhost' && !filter_var($host, FILTER_VALIDATE_IP)) {
             // Basic check: if parts > 2 (sub.domain.com) OR (parts == 2 and ends with localhost)
             if (count($parts) > 2 || (count($parts) === 2 && $parts[1] === 'localhost')) {
                $subdomain = $parts[0];
                if ($subdomain !== 'www') {
                    $isSubdomain = true;
                }
            }
        }

        // 2. If POST request (Form Submission)
        if ($request->isMethod('post')) {

            // === Scenario A: Main Domain (Find Workspace) ===
            if (!$isSubdomain) {
                $request->validate([
                    'company_domain' => 'required|string',
                ]);

                // Find the company by subdomain
                // We strip http/https or full url if user pasted it, just in case
                // But typically user just types "apple"
                $enteredDomain = Str::slug($request->company_domain); 
                
                $company = Company::where('subdomain', $enteredDomain)->first();

                if (!$company) {
                    return back()->with('error', 'Company workspace not found.');
                }

                // Construct redirect URL
                $protocol = $request->secure() ? 'https://' : 'http://';
                $domain = $request->getHost(); // main domain
                $port = $request->getPort();
                $portStr = ($port && $port != 80 && $port != 443) ? ":$port" : "";
                
                // Remove www if present
                $domain = str_replace('www.', '', $domain);

                // FIX: Use localhost if on local env
                if ($domain === '127.0.0.1' || $domain === 'localhost') {
                    $domain = 'localhost';
                } elseif (filter_var($domain, FILTER_VALIDATE_IP)) {
                    // Fallback for other IPs (LAN)
                    $domain = $domain . '.nip.io';
                }

                $newUrl = $protocol . $company->subdomain . '.' . $domain . $portStr . '/login';
                
                return redirect($newUrl);
            }

            // === Scenario B: Subdomain (Actual Login) ===
            $request->validate(
                [
                    'email' => 'required',
                    'password' => 'required'
                ]
            );
            // Support login by either email or username in the same input field
            $identifier = $request->email; // frontend uses 'email' field name
            $loginField = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

            // Find user and log precise failure reasons for diagnostics
            // Important: We must ensure the user belongs to THIS company
            // The SubdomainTenantMiddleware already sets the global scope, 
            // but we should double check or rely on the tenant scope.
            // Since we are in a subdomain, BelongsToCompany scope is active on User model?
            // Actually, BelongsToCompany uses Auth::user() to scope queries... 
            // But we are not logged in yet.
            // We need to manually scope the query to the current company found by middleware.
            
            $currentCompany = $request->attributes->get('current_company');
            
            $query = User::where($loginField, $identifier);
            
            if ($currentCompany) {
                $query->where('company_id', $currentCompany->id);
            }
            
            $user = $query->first();

            if (!$user) {
                logger()->warning('Login failed: user not found', ['identifier' => $identifier, 'field' => $loginField]);
                return redirect()->back()->with('error', 'Incorrect email/username or password');
            }
            if (!Hash::check($request->password, $user->password)) {
                logger()->warning('Login failed: password mismatch', ['identifier' => $identifier, 'field' => $loginField]);
                return redirect()->back()->with('error', 'Incorrect email/username or password');
            }
            if ($user->is_suspended == 1) {
                return redirect()->back()->with('error', 'Your account is temporarily suspended');
            }

            $remember = $request->remember_me ? true : false;
            // Auth::attempt will use the default provider. 
            // We need to pass the extra condition (company_id) to Auth::attempt if possible,
            // or just log them in manually since we verified password.
            // Auth::attempt(['email' => $email, 'password' => $password, 'company_id' => $id]) works if model has that field.
            
            $credentials = [
                $loginField => $identifier,
                'password' => $request->password,
                'company_id' => $currentCompany ? $currentCompany->id : null 
            ];

            if (Auth::attempt($credentials, $remember)) {
                session()->regenerate();
                
                // Set permissions team id now that we are logged in
                if ($currentCompany) {
                    setPermissionsTeamId($currentCompany->id);
                }
        
                return $this->redirectUser();
            } else {
                return redirect()->route('login')->with('error', 'Incorrect email/username or password');
            }

        } else {
            // GET Request
            if (auth()->user()) {
                return $this->redirectUser();
            } else {
                if ($isSubdomain) {
                    return view('frontend.authentication.login', compact('isSubdomain')); // Standard login
                } else {
                    return view('frontend.authentication.find-workspace'); // New view
                }
            }
        }
    }

    public function register(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'name' => 'required',
                'company_name' => 'required|string|max:255',
                'email' => 'email|required|unique:users',
                'password' => 'required|confirmed|min:6'
            ]);

            // Generate subdomain from company name
            $subdomain = Str::slug($request->company_name);
            
            // Ensure unique subdomain (simple check, could be improved)
            if (Company::where('subdomain', $subdomain)->exists()) {
                $subdomain = $subdomain . '-' . Str::random(4);
            }

            // Create Company
            $company = Company::create([
                'name' => $request->company_name,
                'email' => $request->email,
                'subdomain' => $subdomain,
            ]);

            $newUser = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'username' => Str::slug($request->name) . '-' . Str::random(4),
                'password' => Hash::make($request->password),
                'role' => 'admin',
                'company_id' => $company->id,
            ]);

            // Create Admin Role for this company
            // Use query()->create() to bypass Spatie's software-level check that prevents shadowing global roles
            $adminRole = Role::query()->create([
                'name' => 'Admin',
                'guard_name' => 'web',
                'company_id' => $company->id
            ]);

            // Give all permissions to Admin role
            $adminRole->syncPermissions(Permission::all());

            // Assign Admin role to user
            setPermissionsTeamId($company->id);
            $newUser->assignRole($adminRole);
            
            // Construct the redirect URL for the new subdomain
            $protocol = $request->secure() ? 'https://' : 'http://';
            $domain = $request->getHost();
            $port = $request->getPort();
            $portStr = ($port && $port != 80 && $port != 443) ? ":$port" : "";
            
            // Handle localhost or main domain logic
            // Assuming current host is "app.com" or "localhost", new host is "subdomain.app.com" or "subdomain.localhost"
            // If we are already on a subdomain, we might need to strip it, but for now assume registration is on main domain.
            
            // Simple logic: prepend subdomain to the current host (or base domain if configured)
            // For localhost dev: if host is "127.0.0.1", subdomains don't work well without config. 
            // If host is "localhost", "sub.localhost" works on some systems.

            // FIX: Use localhost if on local env
            if ($domain === '127.0.0.1' || $domain === 'localhost') {
                $domain = 'localhost';
            } elseif (filter_var($domain, FILTER_VALIDATE_IP)) {
                $domain = $domain . '.nip.io';
            }
            
            $newUrl = $protocol . $subdomain . '.' . $domain . $portStr . '/login';

            return redirect($newUrl)->with('success', 'Account created successfully! Please login to your company portal.');

        } else {
            if (auth()->user()) {
                return $this->redirectUser();
            } else {
                return view('frontend.authentication.sign-up');
            }
        }
    }

    public function forgetPassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'email' => 'email|required',
            ]);
            $findUser = User::where('email', $request->email)->first();

            $otp = rand(11111, 99999);

            if ($findUser) {
                ForgetPassword::updateOrCreate(
                    [
                        'user_id' => $findUser->id
                    ],
                    [
                        'otp' => $otp,
                        'email' => $findUser->email,
                        'suspend_duration' => now()->addMinutes(5)
                    ]
                );

                session([
                    'user_id' => $findUser->id,
                    'reset-email' => $findUser->email
                ]);

                $mailData = [
                    'title' => readConfig('site_name'),
                    'otp' => $otp,
                    'name' => $findUser->name,
                ];

                Mail::to($findUser->email)->send(new PasswordReset($mailData));

                return redirect()->route('password.reset')->with('success', 'Check your inbox for otp code');
            } else {
                return back()->with('error', 'User not found');
            }
        } else {
            return view('frontend.authentication.forget-password');
        }
    }

    public function resendOtp()
    {
        $findUser = ForgetPassword::where('user_id', session('user_id'))
            ->where('email', session('reset-email'))
            ->first();

        if ($findUser) {
            $user = User::find(session('user_id'));
            $otp = rand(11111, 99999);

            $findUser->otp = $otp;
            $findUser->resent_count++;
            $findUser->suspend_duration = now()->addMinutes(5);
            $findUser->save();

            $mailData = [
                'title' => readConfig('site_name'),
                'otp' => $otp,
                'name' => $user->name,
            ];
            Mail::to($findUser->email)->send(new PasswordReset($mailData));

            return back()->with('success', 'Otp resent successfully');
        } else {
            return back()->with('error', 'Something went wrong');
        }
    }

    public function newPassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'password' => 'required|confirmed|min:6',
            ]);

            $user = User::find(session('user_id'));

            if ($user) {
                $user->password = bcrypt($request->password);
                $user->save();

                session()->forget('user_id');

                return redirect()->route('login')->with('success', 'Password reset successfully');
            } else {
                return redirect()->route('forget.password')->with('error', 'Something went wrong');
            }
        } else {
            return view('frontend.authentication.new-password');
        }
    }

    public function resetPassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'number_1' => 'required',
                'number_2' => 'required',
                'number_3' => 'required',
                'number_4' => 'required',
                'number_5' => 'required',
            ]);
            $otp = $request->number_1 . $request->number_2 . $request->number_3 . $request->number_4 . $request->number_5;

            $record = ForgetPassword::where('email', session('reset-email'))
                ->where('otp', $otp)
                ->first();

            if ($record) {
                $record->delete();
                session()->forget('reset-email');

                if (now()->greaterThan(Carbon::parse($record->suspend_duration))) {
                    return redirect()->route('login')->with('error', 'Otp expired');
                }

                return redirect()->route('new.password');
            } else {
                return back()->with('error', 'Invalid otp');
            }
        } else {
            return view('frontend.authentication.reset');
        }
    }

    public function logout()
    {
        if (auth()->user()) {
            Auth::logout();

            return redirect('/');
        } else {
            return back()->with('error', 'You are not logged in');
        }
    }

    public function update(Request $request)
    {
        $user = User::find(auth()->id());
        // dd($request->all());
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'profile_image' => ['file', new ValidImageType]
        ]);

        if ($request->name !== $user->name) {
            $user->name = $request->name;
        }

        if ($request->email !== $user->email) {
            $user->email = $request->email;
            $user->google_id = null;
        }

        if ($request->hasFile("profile_image")) {
            $user->profile_image = $this->fileHandler->fileUploadAndGetPath($request->file("profile_image"), "/public/media/users");
        }

        if ($request->current_password || $request->new_password || $request->confirm_password) {

            $request->validate([
                'new_password' => 'required|min:6|confirmed',
            ]);

            if ($user->is_google_registered) {
                $user->is_google_registered = false;
            } else {
                $request->validate([
                    'current_password' => 'required',
                ]);

                $currentPassword = $request->current_password;

                if (!Hash::check($currentPassword, $user->password)) {
                    throw ValidationException::withMessages([
                        'current_password' => 'The current password is incorrect',
                    ]);
                }
            }

            $user->password = bcrypt($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Updated Successfully');
    }

    public function redirectUser()
    {
        if (Auth::check()) {
            return redirect()->route('backend.admin.dashboard');
        } else {
            return redirect()->route('login')->with('error', 'You are not logged in');
        }
    }
}
