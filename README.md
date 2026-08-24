# KTD WordPress E-commerce - Website Bán Điện Thoại Cao Cấp

Dự án Xây dựng Website Thương mại Điện tử Bán Điện Thoại Cao Cấp (iPhone, Samsung, OPPO) trên nền tảng WordPress. 

Dự án bao gồm bộ định nghĩa **Kiến trúc Dữ liệu (Data Architecture)** độc lập được đóng gói thành Custom Plugin `phone-store-cpt`, giúp tối ưu hóa khả năng quản lý sản phẩm, thông số kỹ thuật và yêu cầu đơn hàng của khách hàng.

---

## 🎯 1. Tổng Quan Dự Án & Tính Năng Chính

Website được thiết kế chuyên biệt cho ngành hàng thiết bị di động cao cấp với các tính năng kiến trúc lõi:
- 📱 **Quản lý Điện thoại (CPT `phone_product`)**: Lưu trữ và hiển thị các sản phẩm smartphone đến từ các thương hiệu hàng đầu (Apple iPhone, Samsung Galaxy, OPPO Reno...).
- ⚙️ **Thông số Kỹ thuật & Giá bán (Custom Meta Box)**: Nhập liệu chi tiết Giá niêm yết, Giá khuyến mãi, Dung lượng bộ nhớ (Storage), RAM, Chip vi xử lý, Thông số Màn hình, Dung lượng Pin và Trạng thái kho hàng.
- 🏷️ **Phân loại Thương hiệu & Dòng sản phẩm (Custom Taxonomies)**:
  - `phone_brand`: Thương hiệu (Apple/iPhone, Samsung, OPPO, Xiaomi...).
  - `phone_series`: Dòng sản phẩm (iPhone 15 Series, Galaxy S24 Series, Reno Series...).
- 🛒 **Quản lý Đơn hàng & Tư vấn (CPT `phone_order`)**: Tiếp nhận thông tin khách hàng (Họ tên, SĐT, Email, Sản phẩm lựa chọn) và theo dõi trạng thái xử lý đơn hàng.

---

## 📐 2. Sơ Đồ Kiến Trúc Dữ Liệu (ERD Diagram)

Sơ đồ thể hiện mối quan hệ giữa các Thực thể (Entities), Custom Post Types, Custom Taxonomies và Custom Meta Boxes trong hệ thống:

```mermaid
erDiagram
    wp_users ||--o{ phone_product : "Quản trị & Đăng sản phẩm"
    wp_users ||--o{ phone_order : "Tiếp nhận & Xử lý đơn"

    phone_product ||--|{ phone_brand : "Thuộc Thương hiệu (Apple, Samsung, OPPO)"
    phone_product ||--o{ phone_series : "Thuộc Dòng sản phẩm (iPhone 15, S24...)"
    
    phone_order }|--|| phone_product : "Đặt mua / Yêu cầu báo giá"

    phone_product {
        BIGINT ID PK "Khóa chính bài viết"
        string post_title "Tên sản phẩm (VD: iPhone 15 Pro Max 256GB)"
        string post_content "Bài viết giới thiệu chi tiết"
        string _phone_price "Giá niêm yết (VNĐ)"
        string _phone_sale_price "Giá khuyến mãi (VNĐ)"
        string _phone_storage "Dung lượng bộ nhớ (128GB, 256GB, 512GB, 1TB)"
        string _phone_ram "Dung lượng RAM (8GB, 12GB, 16GB)"
        string _phone_chipset "Chip vi xử lý (A17 Pro, Snapdragon 8 Gen 3)"
        string _phone_screen "Kích thước & Công nghệ màn hình"
        string _phone_battery "Dung lượng Pin & Công nghệ sạc"
        string _phone_stock_status "Trạng thái kho hàng (Còn hàng / Hết hàng / Đặt trước)"
    }

    phone_brand {
        BIGINT term_id PK "Khóa chính danh mục"
        string name "Tên thương hiệu (Apple, Samsung, OPPO...)"
        string slug "Đường dẫn SEO"
    }

    phone_series {
        BIGINT term_id PK "Khóa chính thẻ phân loại"
        string name "Dòng sản phẩm (iPhone 15 Series, Galaxy S24...)"
        string slug "Đường dẫn SEO"
    }

    phone_order {
        BIGINT ID PK "Khóa chính đơn hàng"
        string post_title "Tiêu đề yêu cầu / Đơn hàng"
        string _order_customer_name "Họ và tên khách hàng"
        string _order_customer_phone "Số điện thoại liên hệ"
        string _order_customer_email "Địa chỉ Email"
        string _order_product_name "Sản phẩm quan tâm / Chọn mua"
        string _order_status "Trạng thái đơn (Mới nhận, Đã liên hệ, Hoàn thành, Hủy)"
    }
```

---

## 🛠️ 3. Cấu Trúc Mã Nguồn (Repository Structure)

Mã nguồn Data Architecture được đóng gói độc lập theo dạng **Custom Plugin** để đảm bảo dữ liệu không bị mất khi thay đổi Theme giao diện:

```text
KTD_Wordpress_Ecommerce/
├── README.md
├── .gitignore
└── wp-content/
    └── plugins/
        └── phone-store-cpt/
            ├── phone-store-cpt.php               # Entry point khởi tạo Plugin
            ├── assets/
            │   └── admin-style.css               # Giao diện CSS Admin Custom Meta Box
            └── includes/
                ├── class-cpt-registrar.php       # Đăng ký CPT phone_product & phone_order
                ├── class-taxonomy-registrar.php  # Đăng ký Taxonomy phone_brand & phone_series
                ├── class-metabox-product.php     # Xử lý Meta Box cho Điện thoại
                └── class-metabox-order.php       # Xử lý Meta Box cho Đơn hàng
```

---

## 🚀 4. Hướng Dẫn Kích Hoạt Plugin

1. Clone hoặc tải mã nguồn về thư mục `wp-content/plugins/` trong dự án WordPress của bạn.
2. Đăng nhập vào trang **WordPress Admin Dashboard** ➔ **Plugins** ➔ **Installed Plugins**.
3. Tìm plugin tên **"Phone Store Custom Post Types & Meta Boxes"** và nhấn **Activate**.
4. Hai menu mới sẽ xuất hiện trên Sidebar:
   - 📱 **Điện Thoại**: Quản lý sản phẩm, thông số kỹ thuật và thương hiệu.
   - 🛒 **Đơn Hàng / Tư Vấn**: Quản lý thông tin đơn hàng khách đăng ký.
