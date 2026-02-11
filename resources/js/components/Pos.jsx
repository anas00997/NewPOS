import React, {useEffect, useState, useCallback, useRef } from "react";
import axios from "axios";
import Swal from "sweetalert2";
import Cart from "./Cart";
import toast, { Toaster } from "react-hot-toast";
import CustomerSelect from "./CutomerSelect";

import SuccessSound from "../sounds/beep-07a.mp3";
import WarningSound from "../sounds/beep-02.mp3";
import playSound from "../utils/playSound";

export default function Pos() {
    const [newCustomerName, setNewCustomerName] = useState("");
    const [newCustomerPhone, setNewCustomerPhone] = useState("");
    const [newCustomerEmail, setNewCustomerEmail] = useState("");
    const [newCustomerDob, setNewCustomerDob] = useState("");

    const createNewCustomer = async () => {
        if (!newCustomerName || !newCustomerPhone) {
            Swal.fire("Error", "Name and Phone are required fields.", "error");
            return;
        }
        try {
            const response = await axios.post('/admin/customers', {
                name: newCustomerName,
                phone: newCustomerPhone,
                email_address: newCustomerEmail,
                dob: newCustomerDob
            });
            Swal.fire("Success", "Customer created successfully.", "success");
            setNewCustomerName("");
            setNewCustomerPhone("");
            setNewCustomerEmail("");
            setNewCustomerDob("");
            setNewCustomerSelected(response.data.id); // Signal that a new customer has been created and should be selected
            setCustomerId(response.data.id); // Set the newly created customer as selected
        } catch (error) {
            Swal.fire("Error", "Failed to create customer.", "error");
        }
    }
    const [products, setProducts] = useState([]);
    const [carts, setCarts] = useState([]);

    const [total, setTotal] = useState(0);
    const [paid, setPaid] = useState(0);
    const [due, setDue] = useState(0);

    const [customerId, setCustomerId] = useState();
    const [newCustomerSelected, setNewCustomerSelected] = useState(null);
    const [cartUpdated, setCartUpdated] = useState(false);
    const [productUpdated, setProductUpdated] = useState(false);
    const [searchQuery, setSearchQuery] = useState("");
    const [searchBarcode, setSearchBarcode] = useState("");
    const [scanInput, setScanInput] = useState("");
    const [scanning, setScanning] = useState(false);
    const [detectorSupported, setDetectorSupported] = useState(
        typeof window !== "undefined" && "BarcodeDetector" in window
    );
    const videoRef = useRef(null);
    const streamRef = useRef(null);
    const detectorRef = useRef(null);
    const scanTimerRef = useRef(null);
    const { protocol, hostname, port } = window.location;
    const [currentPage, setCurrentPage] = useState(1);
    const [totalPages, setTotalPages] = useState(0);
    const [loading, setLoading] = useState(false);
    const barcodeInputRef = useRef(null);

    const handleBarcodeScan = async (barcode) => {
        try {
            const res = await axios.get('/admin/get/products', {
                params: { barcode: barcode.trim() },
            });
            const product = res.data.data || res.data;
            if (product && product.id) {
                addProductToCart(product.id, product.price);
                toast.success(`Scanned: ${product.name}`);
            }
        } catch (error) {
            console.error("Barcode scan error:", error);
            if (error.response && error.response.status === 404) {
                const sku = error.response.data.sku;
                Swal.fire({
                    title: 'Product Not Found',
                    text: `Barcode ${sku} is not in the system. Would you like to add it quickly?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Add it',
                    cancelButtonText: 'No, Cancel'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        const { value: formValues } = await Swal.fire({
                            title: 'Quick Add Product',
                            html:
                                `<input id="swal-input1" class="swal2-input" placeholder="Product Name" value="${sku}">` +
                                '<input id="swal-input2" class="swal2-input" type="number" placeholder="Selling Price">',
                            focusConfirm: false,
                            preConfirm: () => {
                                return [
                                    document.getElementById('swal-input1').value,
                                    document.getElementById('swal-input2').value
                                ]
                            }
                        });

                        if (formValues && formValues[0] && formValues[1]) {
                            try {
                                const createRes = await axios.post('/admin/quick-create-product', {
                                    name: formValues[0],
                                    price: formValues[1],
                                    sku: sku
                                });
                                const newProduct = createRes.data.product;
                                addProductToCart(newProduct.id, newProduct.price);
                                setProductUpdated(!productUpdated);
                                toast.success("Product created and added to cart");
                            } catch (createError) {
                                toast.error(createError.response?.data?.message || "Failed to create product");
                            }
                        }
                    }
                });
            } else {
                toast.error("Error searching for barcode");
            }
        }
    };

    useEffect(() => {
        let buffer = "";
        let lastKeyTime = Date.now();

        const handleKeyDown = (e) => {
            const currentTime = Date.now();
            
            // Physical barcode scanners are very fast (usually < 30ms between characters)
            if (currentTime - lastKeyTime > 100) {
                buffer = ""; // Reset buffer if typing is slow (manual entry)
            }
            
            lastKeyTime = currentTime;

            if (e.key === 'Enter') {
                if (buffer.length > 2) { // Minimum barcode length
                    handleBarcodeScan(buffer);
                    e.preventDefault();
                }
                buffer = "";
            } else if (e.key.length === 1) {
                buffer += e.key;
            }
        };

        window.addEventListener('keydown', handleKeyDown);
        return () => window.removeEventListener('keydown', handleKeyDown);
    }, []);

    const fullDomainWithPort = `${protocol}//${hostname}${
        port ? `:${port}` : ""
    }`;
    const getProducts = useCallback(
        async (search = "", page = 1, barcode = "") => {
            setLoading(true);
            try {
                const res = await axios.get('/admin/get/products', {
                    params: { search, page, barcode },
                });
                const productsData = res.data;
                setProducts((prev) => [...prev, ...productsData.data]); // Append new products
                if (productsData.data.length === 1 && barcode != "") {
                    addProductToCart(productsData.data[0].id, productsData.data[0].price);
                    getCarts();
                }
                setTotalPages(productsData.meta.last_page); // Get total pages
            } catch (error) {
                console.error("Error fetching products:", error);
            } finally {
                setLoading(false); // Set loading to false
            }
        },
        []
    );
    const getUpdatedProducts = useCallback(async () => {
        try {
            const res = await axios.get('/admin/get/products');
            const productsData = res.data;
            setProducts(productsData.data);
            setTotalPages(productsData.meta.last_page); // Get total pages
        } catch (error) {
            console.error("Error fetching products:", error);
        }
    }, []);
    useEffect(() => {
        getUpdatedProducts();
    }, [productUpdated]);

    const getCarts = async () => {
        try {
            const res = await axios.get('/admin/cart');
            const data = res.data;
            setTotal(res.data.total);
            setCarts(data?.carts);
        } catch (error) {
            console.error("Error fetching carts:", error);
        }
    };

    useEffect(() => {
        getCarts();
    }, []);

    useEffect(() => {
        getCarts();
    }, [cartUpdated]);

    useEffect(() => {
        setDue(total - paid);
    }, [total, paid]);


    useEffect(() => {
        if (searchQuery) {
            setProducts([]);
            getProducts(searchQuery, currentPage, "");
        }
        setSearchBarcode("");
    }, [currentPage, searchQuery]);

    useEffect(() => {
        if (searchBarcode) {
            setProducts([]);
           getProducts("", currentPage, searchBarcode);
        }
    }, [searchBarcode]);

    // Start camera scanning using native BarcodeDetector (if supported)
    const startCameraScan = async () => {
        if (!detectorSupported) {
            toast.error("Camera scanning supported nahi hai is browser me");
            return;
        }
        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: "environment" },
                audio: false,
            });
            streamRef.current = stream;
            if (videoRef.current) {
                videoRef.current.srcObject = stream;
                await videoRef.current.play();
            }
            // Instantiate detector for common formats
            detectorRef.current = new window.BarcodeDetector({
                formats: [
                    "qr_code",
                    "code_128",
                    "code_39",
                    "ean_13",
                    "ean_8",
                ],
            });
            setScanning(true);
            scanTimerRef.current = setInterval(async () => {
                try {
                    if (!videoRef.current) return;
                    const codes = await detectorRef.current.detect(
                        videoRef.current
                    );
                    if (codes && codes.length > 0) {
                        const code = codes[0]?.rawValue || "";
                        if (code) {
                            setSearchBarcode(code);
                            playSound(SuccessSound);
                            stopCameraScan();
                        }
                    }
                } catch (err) {
                    // Ignore intermittent detect errors
                }
            }, 350);
        } catch (err) {
            toast.error("Camera access issue: " + (err?.message || "Unknown"));
        }
    };

    const stopCameraScan = () => {
        setScanning(false);
        if (scanTimerRef.current) {
            clearInterval(scanTimerRef.current);
            scanTimerRef.current = null;
        }
        if (videoRef.current) {
            videoRef.current.pause();
            videoRef.current.srcObject = null;
        }
        if (streamRef.current) {
            streamRef.current.getTracks().forEach((t) => t.stop());
            streamRef.current = null;
        }
    };

    // Infinite scroll logic
    useEffect(() => {
        const handleScroll = () => {
            if (
                window.innerHeight + document.documentElement.scrollTop >=
                document.documentElement.offsetHeight
            ) {
                // Load next page if not on the last page
                if (currentPage < totalPages) {
                    setCurrentPage((prev) => prev + 1);
                }
            }
        };

        window.addEventListener("scroll", handleScroll);
        return () => {
            window.removeEventListener("scroll", handleScroll);
        };
    }, [currentPage, totalPages]);

    function addProductToCart(id, price = 0) {
        axios
            .post("/admin/cart", { id, price })
            .then((res) => {
                setCartUpdated(!cartUpdated);
                playSound(SuccessSound);
                toast.success(res?.data?.message);
            })
            .catch((err) => {
                playSound(WarningSound);
                toast.error(err.response.data.message);
            });
    }
    function cartEmpty() {
        if (total <= 0) {
            return;
        }
        Swal.fire({
            title: "Are you sure you want to delete Cart?",
            showDenyButton: true,
            confirmButtonText: "Yes",
            denyButtonText: "No",
            customClass: {
                actions: "my-actions",
                cancelButton: "order-1 right-gap",
                confirmButton: "order-2",
                denyButton: "order-3",
            },
        }).then((result) => {
            if (result.isConfirmed) {
                axios
                    .put("/admin/cart/empty")
                    .then((res) => {
                        setCartUpdated(!cartUpdated);
                        playSound(SuccessSound);
                        toast.success(res?.data?.message);
                    })
                    .catch((err) => {
                        playSound(WarningSound);
                        toast.error(err.response.data.message);
                    });
            } else if (result.isDenied) {
                return;
            }
        });
    }
    function orderCreate() {
        if (total <= 0) {
            return;
        }
        if (!customerId) {
            toast.error("Please select customer");
            return;
        }
        Swal.fire({
            title: `Are you sure you want to complete this order?`,
            showDenyButton: true,
            confirmButtonText: "Yes",
            denyButtonText: "No",
            customClass: {
                actions: "my-actions",
                cancelButton: "order-1 right-gap",
                confirmButton: "order-2",
                denyButton: "order-3",
            },
        }).then((result) => {
            if (result.isConfirmed) {
                axios
                    .put("/admin/order/create", {
                        customer_id: customerId,
                        paid_amount: paid,
                    })
                    .then((res) => {
                        setCartUpdated(!cartUpdated);
                        setProductUpdated(!productUpdated);
                        toast.success(res?.data?.message);
                        // window.location.href = `orders/invoice/${res?.data?.order?.id}`;
                        window.location.href = `orders/pos-invoice/${res?.data?.order?.id}`;
                    })
                    .catch((err) => {
                        toast.error(err.response.data.message);
                    });
            } else if (result.isDenied) {
                return;
            }
        });
    }
    return (
        <>  
           <div className="col-12">
                                    <CustomerSelect
                                        setCustomerId={setCustomerId}
                                        newCustomerSelected={newCustomerSelected}
                                    />
                                    <div className="mt-3">
                                        <h5>Add New Customer</h5>
                                        <div className="row">
                                            <div className="col-md-6">
                                                <div className="form-group">
                                                    <label htmlFor="name">Name *</label>
                                                    <input
                                                        type="text"
                                                        className="form-control"
                                                        id="name"
                                                        placeholder="Enter title"
                                                        value={newCustomerName}
                                                        onChange={(e) =>
                                                            setNewCustomerName(
                                                                e.target.value
                                                            )
                                                        }
                                                    />
                                                </div>
                                            </div>
                                            <div className="col-md-6">
                                                <div className="form-group">
                                                    <label htmlFor="phone">Phone</label>
                                                    <input
                                                        type="text"
                                                        className="form-control"
                                                        id="phone"
                                                        placeholder="Enter phone"
                                                        value={newCustomerPhone}
                                                        onChange={(e) =>
                                                            setNewCustomerPhone(
                                                                e.target.value
                                                            )
                                                        }
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div className="row">
                                            <div className="col-md-6">
                                                <div className="form-group">
                                                    <label htmlFor="email">Email Address</label>
                                                    <input
                                                        type="email"
                                                        className="form-control"
                                                        id="email"
                                                        placeholder="Enter Email Address"
                                                        value={newCustomerEmail}
                                                        onChange={(e) =>
                                                            setNewCustomerEmail(
                                                                e.target.value
                                                            )
                                                        }
                                                    />
                                                </div>
                                            </div>
                                            <div className="col-md-6">
                                                <div className="form-group">
                                                    <label htmlFor="dob">DOB</label>
                                                    <input
                                                        type="date"
                                                        className="form-control"
                                                        id="dob"
                                                        placeholder="Enter product expire date"
                                                        value={newCustomerDob}
                                                        onChange={(e) =>
                                                            setNewCustomerDob(
                                                                e.target.value
                                                            )
                                                        }
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <button
                                            className="btn btn-info mb-3"
                                            onClick={createNewCustomer}
                                        >
                                            Create Customer
                                        </button>
                                    </div>
                                </div>
            <div className="card">
                {/* <div class="mt-n5 mb-3 d-flex justify-content-end">
                    <a
                        href="/admin"
                        className="btn bg-gradient-primary mr-2"
                    >
                        Dashboard
                    </a>
                    <a
                        href="/admin/ordersma"
                        className="btn bg-gradient-primary"
                    >
                        Orders
                    </a>
                </div> */}

                <div className="card-body p-2 p-md-4 pt-0">
                    <div className="row">
                        <div className="col-md-6 col-lg-5 mb-2">
                            <div className="row mb-2">
                             
                                <div className="card">
                                    <div className="card-body">
                                        <div className="row text-bold mb-1">
                                            <div className="col">Bill Amount:</div>
                                            <div className="col text-right mr-2">
                                                <input
                                                    type="number"
                                                    className="form-control form-control-sm text-right"
                                                    value={total}
                                                    onChange={(e) => setTotal(e.target.value)}
                                                />
                                            </div>
                                        </div>
                                       
                                        <div className="row text-bold mb-1">
                                            <div className="col">Due:</div>
                                            <div className="col text-right mr-2">
                                                {due.toFixed(2)}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {/* <div className="col-6">
                                <form className="form">
                                    <input
                                        type="text"
                                        className="form-control"
                                        placeholder="Enter barcode"
                                        value={searchQuery}
                                        onChange={(e) =>
                                            setSearchQuery(e.target.value)
                                        }
                                    />
                                </form>
                            </div> */}
                            </div>
                            <Cart
                                carts={carts}
                                setCartUpdated={setCartUpdated}
                                cartUpdated={cartUpdated}
                            />

                            <div className="row">
                                <div className="col">
                                    <button
                                        onClick={() => cartEmpty()}
                                        type="button"
                                        className="btn bg-gradient-danger btn-block text-white text-bold"
                                    >
                                        Clear Cart
                                    </button>
                                </div>
                                <div className="col">
                                    <button
                                        onClick={() => {
                                            orderCreate();
                                        }}
                                        type="button"
                                        className="btn bg-gradient-primary btn-block text-white text-bold"
                                    >
                                        Checkout
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div className="col-md-6 col-lg-7">
                            <div className="row">
                                <div className="mb-2 col-md-12">
                                    <input
                                        type="text"
                                        className="form-control"
                                        placeholder="Enter Product Name"
                                        value={searchQuery}
                                        onChange={(e) =>
                                            setSearchQuery(e.target.value)
                                        }
                                    />
                                </div>
                            </div>
                            <div className="row products-card-container">
                                {products.length > 0 &&
                                    products.map((product, index) => (
                                        <div
                                    onClick={async (e) => {
                                        const { value: price } = await Swal.fire({
                                            title: `Enter price for ${product.name}`,
                                            input: 'number',
                                            inputLabel: 'Price',
                                            inputValue: product.price,
                                            showCancelButton: true,
                                            inputValidator: (value) => {
                                                if (!value) {
                                                    return 'You need to enter a price!';
                                                }
                                                if (isNaN(value) || parseFloat(value) <= 0) {
                                                    return 'Please enter a valid positive number for the price!';
                                                }
                                            }
                                        });

                                        if (price) {
                                            addProductToCart(product.id, parseFloat(price));
                                        }
                                    }}
                                            className="col-6 col-md-4 col-lg-3 mb-3"
                                            key={index}
                                            style={{ cursor: "pointer" }}
                                        >
                                            <div className="text-center">
                                                <img
                                                    src={`${fullDomainWithPort}/storage/${product.image}`}
                                                    alt={product.name}
                                                    className="mr-2 img-thumb"
                                                    onError={(e) => {
                                                        e.target.onerror = null;
                                                        e.target.src = `${fullDomainWithPort}/assets/images/no-image.png`;
                                                    }}
                                                    width={120}
                                                    height={100}
                                                />
                                                <div className="product-details">
                                                    <p className="mb-0 text-bold product-name">
                                                        {product.name}
                                                    </p>

                                                </div>
                                            </div>
                                        </div>
                                    ))}
                            </div>
                            {loading && (
                                <div className="loading-more">
                                    Loading more...
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
            <Toaster position="top-right" reverseOrder={false} />
        </>
    );
}