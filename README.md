# Hệ thống Quản lý Hàng hóa - Frontend

Đây là giao diện frontend hoàn chỉnh cho hệ thống quản lý hàng hóa, được trích xuất từ template Laravel Blade và chuyển đổi thành HTML, CSS, JavaScript thuần.

## 📁 Cấu trúc thư mục

```
/
├── index.html                 # Trang chính
├── css/
│   ├── medicine-management.css    # CSS chính cho giao diện
│   └── responsive.css            # CSS responsive cho mobile/tablet
├── js/
│   └── products/
│       ├── medicine-management.js # JavaScript quản lý thuốc
│       ├── goods-management.js    # JavaScript quản lý hàng hóa
│       └── service-management.js  # JavaScript quản lý dịch vụ
│   └── forms.js                  # JavaScript xử lý form chung
└── README.md                     # Tài liệu hướng dẫn
```

## 🚀 Tính năng chính

### 1. Giao diện quản lý sản phẩm
- **Header điều khiển**: Thanh tìm kiếm, nút tạo mới, import/export
- **Sidebar lọc**: Lọc theo nhóm hàng, tồn kho, nhà cung cấp, vị trí, loại hàng
- **Bảng sản phẩm**: Hiển thị danh sách thuốc, hàng hóa, dịch vụ
- **Chi tiết mở rộng**: Click vào sản phẩm để xem thông tin chi tiết

### 2. Quản lý 3 loại sản phẩm
- **Thuốc (Medicine)**: Mã thuốc, hoạt chất, đường dùng, số đăng ký
- **Hàng hóa (Goods)**: Đơn vị tính, quy cách đóng gói, quản lý theo lô
- **Dịch vụ (Service)**: Hình thức thực hiện, thời gian, trạng thái

### 3. Chức năng tương tác
- **Tìm kiếm**: Theo mã hàng và tên hàng với debounce
- **Lọc**: Đa tiêu chí với filter động
- **Chi tiết**: Hiển thị thông tin đầy đủ với tabs
- **Thao tác**: Chỉnh sửa, xóa, in tem, thiết lập đơn vị

## 🎨 Thiết kế giao diện

### Responsive Design
- **Desktop**: Layout 2 cột với sidebar và main content
- **Tablet**: Layout responsive với điều chỉnh font size và spacing
- **Mobile**: Layout 1 cột với horizontal scroll cho bảng

### Color Scheme
- **Primary**: #007bff (Bootstrap Blue)
- **Success**: #28a745 (Green) - cho thuốc
- **Info**: #17a2b8 (Teal) - cho dịch vụ  
- **Warning**: #ffc107 (Yellow) - cho trạng thái
- **Secondary**: #6c757d (Gray)

### Typography
- **Font Family**: System fonts (San Francisco, Segoe UI, Roboto)
- **Font Sizes**: Responsive từ 0.7rem (mobile) đến 1.5rem (desktop)

## 💻 Cách sử dụng

### 1. Mở trang web
```bash
# Mở file index.html trong trình duyệt
open index.html
# hoặc
python -m http.server 8000  # Chạy local server
```

### 2. Tương tác với giao diện

#### Tìm kiếm sản phẩm:
- Nhập từ khóa vào ô "Theo mã, tên hàng"
- Kết quả hiển thị real-time với debounce 300ms

#### Lọc sản phẩm:
- **Nhóm hàng**: Chọn từ dropdown
- **Tồn kho**: Tất cả, Còn hàng, Hết hàng, Sắp hết
- **Nhà cung cấp**: Chọn nhà cung cấp cụ thể
- **Vị trí**: Chọn vị trí lưu trữ
- **Loại hàng**: Thuốc, Hàng hóa, Dịch vụ

#### Xem chi tiết sản phẩm:
- Click vào bất kỳ dòng nào trong bảng
- Chi tiết hiển thị với 3 tabs:
  - **Thông tin**: Thông tin cơ bản và chuyên môn
  - **Mô tả**: Mô tả chi tiết và ghi chú
  - **Tồn kho**: Thông tin tồn kho (chỉ thuốc và hàng hóa)

#### Thao tác với sản phẩm:
- **Chỉnh sửa**: Nút màu xanh
- **Xóa**: Nút xám với xác nhận
- **In tem mã**: In mã vạch sản phẩm
- **Thiết lập đơn vị**: Cấu hình đơn vị tính

### 3. Tạo mới sản phẩm
- Click dropdown "Tạo mới"
- Chọn loại: Thuốc, Hàng hóa, Dịch vụ, Combo
- Modal form sẽ hiển thị (cần implement backend)

## 🔧 Tùy chỉnh và mở rộng

### 1. Thêm sản phẩm mới
Để thêm sản phẩm mới vào bảng:

```javascript
// Thêm dòng thuốc mới
const newMedicineRow = `
<tr class="product-row medicine-row" data-product-id="new-id">
    <!-- Nội dung dòng -->
</tr>`;
document.querySelector('#productTableBody').insertAdjacentHTML('beforeend', newMedicineRow);
```

### 2. Tùy chỉnh filter
Thêm filter mới trong sidebar:

```html
<div class="filter-section">
    <label>Filter mới</label>
    <select class="form-select form-select-sm" onchange="customFilter()">
        <option value="">Chọn...</option>
    </select>
</div>
```

### 3. Thêm chức năng mới
Tạo function mới trong JavaScript:

```javascript
function newFeature() {
    // Logic chức năng mới
    console.log('Chức năng mới');
}

// Đăng ký global
window.newFeature = newFeature;
```

## 📱 Responsive Breakpoints

- **Extra Large**: ≥1200px - Desktop lớn
- **Large**: 992px-1199px - Desktop
- **Medium**: 768px-991px - Tablet
- **Small**: 576px-767px - Tablet nhỏ/Phone landscape
- **Extra Small**: <576px - Phone portrait

## 🎯 Tối ưu hóa

### Performance
- **Debounced search**: Giảm số lần tìm kiếm
- **Lazy loading**: Chi tiết sản phẩm load khi cần
- **Event delegation**: Xử lý event hiệu quả
- **CSS optimization**: Sử dụng class có sẵn của Bootstrap

### SEO & Accessibility
- **Semantic HTML**: Sử dụng đúng thẻ HTML
- **ARIA labels**: Hỗ trợ screen reader
- **Keyboard navigation**: Điều hướng bằng bàn phím
- **Alt text**: Mô tả cho hình ảnh

## 🔌 Tích hợp Backend

Để tích hợp với backend API:

### 1. Cấu hình API endpoints
```javascript
const API_BASE_URL = 'https://api.example.com';
const ENDPOINTS = {
    medicines: '/api/medicines',
    goods: '/api/goods',
    services: '/api/services',
    categories: '/api/categories'
};
```

### 2. Fetch data từ API
```javascript
async function loadProducts() {
    try {
        const response = await fetch(`${API_BASE_URL}${ENDPOINTS.medicines}`);
        const data = await response.json();
        renderProducts(data);
    } catch (error) {
        console.error('Error loading products:', error);
    }
}
```

### 3. Submit form data
```javascript
async function createProduct(formData) {
    try {
        const response = await fetch(`${API_BASE_URL}${ENDPOINTS.medicines}`, {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.success) {
            showAlert('success', 'Tạo sản phẩm thành công!');
        }
    } catch (error) {
        showAlert('error', 'Có lỗi xảy ra!');
    }
}
```

## 🐛 Debugging

### 1. Console logs
Mở Developer Tools để xem logs:
- Khởi tạo: "Medicine management initialized"
- Tìm kiếm: "Loading details for product {id}"
- Lỗi: Chi tiết lỗi trong console

### 2. Kiểm tra elements
- Kiểm tra class CSS được áp dụng đúng
- Xem data attributes của elements
- Kiểm tra event listeners

## 📄 License

MIT License - Sử dụng tự do cho mục đích thương mại và phi thương mại.

## 🤝 Đóng góp

1. Fork repository
2. Tạo feature branch
3. Commit changes
4. Push to branch
5. Create Pull Request

## 📞 Hỗ trợ

Nếu có vấn đề hoặc câu hỏi, vui lòng tạo issue trên GitHub hoặc liên hệ qua email.

---

**Lưu ý**: Đây là frontend thuần, cần tích hợp với backend để có đầy đủ chức năng CRUD và xử lý dữ liệu thực tế.
