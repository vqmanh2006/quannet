# Quannet - Quản lý quán net (PHP MVC thuần)

Nền tảng đầu tiên cho project quản lý quán net, xây bằng PHP 8.x + MySQL (PDO), không dùng framework lớn.

## 1) Cấu trúc thư mục

```text
app/
  Controllers/
  Models/
  Views/
    layouts/
    auth/
    dashboard/
    computers/
    customers/
    sessions/
core/
config/
public/
  css/
  js/
  uploads/
database/
```

## 2) Yêu cầu môi trường

- PHP 8.x (khuyến nghị 8.1+)
- MySQL/MariaDB
- Apache (XAMPP)
- Extension PDO, pdo_mysql

## 3) Cấu hình database

Mặc định app đọc tại `config/database.php` (an toàn với local demo):

- host: `127.0.0.1`
- port: `3306`
- database: `quan_net`
- username: `root`
- password: `` (rỗng)

Bạn có thể sửa trực tiếp file này cho môi trường local của bạn.

## 4) Import dữ liệu mẫu

Import file:

- `database/quan_net.sql`

File đã có seed tài khoản demo (mật khẩu hash bằng `password_hash`, tương thích `password_verify`).

## 5) Tài khoản demo

- Admin: `admin` / `admin123`
- Staff: `staff` / `staff123`

## 6) Chạy bằng XAMPP

### Cách A (khuyến nghị): trỏ DocumentRoot vào `public/`

Ví dụ VirtualHost:

```apache
<VirtualHost *:80>
    ServerName quannet.local
    DocumentRoot "C:/xampp/htdocs/quannet/public"
    <Directory "C:/xampp/htdocs/quannet/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Sau đó thêm host trong file hosts:

```text
127.0.0.1 quannet.local
```

Mở: `http://quannet.local/`

### Cách B: đặt project trong `htdocs`

Nếu chưa cấu hình VirtualHost, truy cập:

`http://localhost/quannet/public/`

## 7) Rewrite URL

- `public/.htaccess`: rewrite route về `public/index.php`
- `.htaccess` ở root: forward request vào thư mục `public/`

## 8) Route chính

- `GET /login`, `POST /login`, `GET /logout`
- `GET /dashboard`
- Máy trạm: `/computers`, `/computers/create`, `/computers/edit?id=...`
- Khách hàng: `/customers`, `/customers/create`, `/customers/edit?id=...`
- Phiên chơi: `/sessions`, `/sessions/open`, `/sessions/close?id=...`

## 9) Nghiệp vụ chính đã có

- Đăng nhập/đăng xuất bằng session.
- Kiểm tra auth cho trang quản trị.
- CRUD máy trạm (prepared statements).
- Không cho xóa máy khi đang có phiên `playing`.
- CRUD khách hàng.
- Mở phiên cho máy `available`.
- Không cho mở 2 phiên đồng thời trên cùng máy.
- Đóng phiên + tính phí theo thời gian.
- Transaction khi mở/đóng phiên để đồng bộ máy và phiên.
- Dashboard: tổng máy, trạng thái máy, số phiên hoạt động, doanh thu hôm nay.

## 10) Kiểm thử nhanh thủ công

1. Đăng nhập bằng tài khoản demo.
2. Thêm/sửa/xóa khách hàng.
3. Thêm máy mới ở trạng thái `available`.
4. Mở phiên cho máy vừa tạo.
5. Thử mở thêm phiên trên cùng máy (phải bị chặn).
6. Đóng phiên, kiểm tra:
   - trạng thái phiên chuyển `completed`
   - máy quay về `available`
   - tổng phí được tính.
7. Thử xóa máy đang `playing` (phải bị chặn).

## 11) Lint cơ bản

```bash
find . -name "*.php" -not -path "./vendor/*" -print0 | xargs -0 -n1 php -l
```
