import React, { useState, useEffect } from "react";
import CreatableSelect from "react-select/creatable";
import axios from "axios";

const CustomerSelect = ({ setCustomerId, newCustomerSelected }) => {
    const [customers, setCustomers] = useState([]);
    const [selectedCustomer, setSelectedCustomer] = useState({value:1,label:"Walking Customer"});

    // Fetch existing customers from the backend
    useEffect(() => {
      axios.get("/admin/get/customers").then((response) => {
            const customerOptions = response?.data?.map((customer) => ({
                value: customer.id,
                label: customer.name,
            }));
            setCustomers(customerOptions);
        });
    }, [newCustomerSelected]);

    useEffect(() => {
        if (newCustomerSelected) {
            axios.get(`/admin/get/customers/${newCustomerSelected}`).then((response) => {
                const customer = response.data;
                if (customer) {
                    setSelectedCustomer({ value: customer.id, label: customer.name });
                }
            }).catch(error => {
                console.error("Error fetching new customer details:", error);
            });
        }
    }, [newCustomerSelected]);

  useEffect(() => {
    setCustomerId(selectedCustomer?.value);
  }, [selectedCustomer]);

    const handleCreateCustomer = (inputValue) => {
        axios
            .post("/admin/create/customers", { name: inputValue })
            .then((response) => {
                const newCustomer = response.data;
                const newOption = {
                    value: newCustomer.id,
                    label: newCustomer.name,
                };
                setCustomers((prev) => [newOption,...prev]);
                setSelectedCustomer(newOption);
            })
            .catch((error) => {
                console.error("Error creating customer:", error);
            });
    };

    const handleChange = (newValue) => {
        setSelectedCustomer(newValue);
    };

    return (
        <CreatableSelect
            isClearable
            options={customers}
            onChange={handleChange}
            onCreateOption={handleCreateCustomer} // Handle creating a new customer
            value={selectedCustomer}
            placeholder="Select or create customer"
        />
    );
};

export default CustomerSelect;
