/**
 * Address Selection Script for Vietnam Provinces/Districts/Wards
 * Using the API from https://provinces.open-api.vn/
 */

const API_URL = 'https://provinces.open-api.vn/api/v1';

document.addEventListener('DOMContentLoaded', function() {
    // Get DOM elements
    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');
    const addressDetail = document.getElementById('address_detail');
    const fullAddress = document.getElementById('full_address');
    const shippingFee = document.getElementById('shipping_fee');
    const shippingFeeAmount = document.getElementById('shipping_fee_amount');
    const totalWithShipping = document.getElementById('total_with_shipping');
    
    // Original total from page
    const originalTotal = parseFloat(document.getElementById('order_total').dataset.total || 0);

    // Load provinces on page load
    loadProvinces();

    // Event listeners for selects
    if (provinceSelect) {
        provinceSelect.addEventListener('change', function() {
            loadDistricts(this.value);
            updateShippingFee(this.value);
            updateFullAddress();
        });
    }

    if (districtSelect) {
        districtSelect.addEventListener('change', function() {
            loadWards(this.value);
            updateFullAddress();
        });
    }

    if (wardSelect) {
        wardSelect.addEventListener('change', function() {
            updateFullAddress();
        });
    }

    if (addressDetail) {
        addressDetail.addEventListener('input', function() {
            updateFullAddress();
        });
    }

    /**
     * Load provinces from API
     */
    function loadProvinces() {
        fetch(`${API_URL}/p`)
            .then(response => response.json())
            .then(data => {
                if (provinceSelect) {
                    provinceSelect.innerHTML = '<option value="">Chọn Tỉnh/Thành phố</option>';
                    
                    data.forEach(province => {
                        const option = document.createElement('option');
                        option.value = province.code;
                        option.setAttribute('data-name', province.name);
                        option.textContent = province.name;
                        provinceSelect.appendChild(option);
                    });
                }
            })
            .catch(error => console.error('Error loading provinces:', error));
    }

    /**
     * Load districts based on province code
     */
    function loadDistricts(provinceCode) {
        if (!provinceCode) {
            if (districtSelect) {
                districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
                districtSelect.disabled = true;
            }
            
            if (wardSelect) {
                wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                wardSelect.disabled = true;
            }
            return;
        }

        fetch(`${API_URL}/p/${provinceCode}?depth=2`)
            .then(response => response.json())
            .then(data => {
                if (districtSelect) {
                    districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
                    districtSelect.disabled = false;
                    
                    data.districts.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.code;
                        option.setAttribute('data-name', district.name);
                        option.textContent = district.name;
                        districtSelect.appendChild(option);
                    });
                }
                
                // Reset ward select
                if (wardSelect) {
                    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                    wardSelect.disabled = true;
                }
            })
            .catch(error => console.error('Error loading districts:', error));
    }

    /**
     * Load wards based on district code
     */
    function loadWards(districtCode) {
        if (!districtCode) {
            if (wardSelect) {
                wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                wardSelect.disabled = true;
            }
            return;
        }

        fetch(`${API_URL}/d/${districtCode}?depth=2`)
            .then(response => response.json())
            .then(data => {
                if (wardSelect) {
                    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                    wardSelect.disabled = false;
                    
                    data.wards.forEach(ward => {
                        const option = document.createElement('option');
                        option.value = ward.code;
                        option.setAttribute('data-name', ward.name);
                        option.textContent = ward.name;
                        wardSelect.appendChild(option);
                    });
                }
            })
            .catch(error => console.error('Error loading wards:', error));
    }

    /**
     * Update the hidden full address field
     */
    function updateFullAddress() {
        if (!fullAddress) return;
        
        const detailValue = addressDetail ? addressDetail.value.trim() : '';
        const wardText = wardSelect && wardSelect.selectedIndex > 0 
            ? wardSelect.options[wardSelect.selectedIndex].text 
            : '';
        const districtText = districtSelect && districtSelect.selectedIndex > 0 
            ? districtSelect.options[districtSelect.selectedIndex].text 
            : '';
        const provinceText = provinceSelect && provinceSelect.selectedIndex > 0 
            ? provinceSelect.options[provinceSelect.selectedIndex].text 
            : '';
        
        let addressParts = [];
        if (detailValue) addressParts.push(detailValue);
        if (wardText) addressParts.push(wardText);
        if (districtText) addressParts.push(districtText);
        if (provinceText) addressParts.push(provinceText);
        
        fullAddress.value = addressParts.join(', ');
    }

    /**
     * Calculate and update shipping fee based on province
     */
    function updateShippingFee(provinceCode) {
        if (!shippingFee || !shippingFeeAmount || !totalWithShipping) return;
        
        // Check if selected province is Hanoi (Hà Nội has code 01)
        const isHanoi = provinceCode === '01';
        
        // Set shipping fee: 25k for Hanoi, 35k for others
        const fee = isHanoi ? 25000 : 35000;
        
        // Update UI
        shippingFeeAmount.textContent = formatCurrency(fee);
        shippingFee.value = fee;
        
        // Calculate and update total with shipping
        const newTotal = originalTotal + fee;
        totalWithShipping.textContent = formatCurrency(newTotal);
    }

    /**
     * Format number to Vietnamese currency
     */
    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND',
            minimumFractionDigits: 0
        }).format(amount);
    }
});