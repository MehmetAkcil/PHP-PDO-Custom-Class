# Database Class
`Database` sınıfı, PHP projelerinde MySQL veritabanı işlemlerini kolaylaştırmak için tasarlanmış bir yardımcı sınıftır. Bu sınıf, veritabanı bağlantısını oluşturmanıza, sorguları çalıştırmanıza, veri eklemenize, güncellemenize, silmenize, sorguları filtrelemenize ve diğer birçok veritabanı işlemini gerçekleştirmenize yardımcı olur. 

## Kurulum

1. Projenizin kök dizininde `Database.php` dosyasını indirin veya kopyalayın. 
2. PHP projenizin içinde `require_once  'Database.php';` satırını ekleyin.

## Bağlantı Oluşturma

    $host = 'localhost'; 
    $dbname = 'mydatabase'; 
    $username = 'myusername'; 
    $password = 'mypassword'; 
    $database = new  Database($host, $dbname, $username, $password);

## Metodlar

### `query($sql, $params = array())`
Veritabanında bir sorgu çalıştırır.

-   `$sql`: Çalıştırılacak SQL sorgusu.
-   `$params` (isteğe bağlı): SQL sorgusu için parametreler.

Örnek kullanım:

    $sql = "SELECT * FROM users WHERE id = :id"; 
    $params = array(':id' => 1); 
    $result = $database->query($sql, $params);

### `findAll($table)`
Belirtilen tablodaki tüm kayıtları getirir.

 - `$table`: Verilerin çekileceği tablo adı.

Örnek kullanım:

    $result = $database->findAll('users');

### `insert($table, $data)`
Belirtilen tabloya yeni bir kayıt ekler.

-   `$table`: Yeni kaydın ekleneceği tablo adı.
-   `$data`: Eklenecek verilerin bir ilişkisel dizi olarak sağlanması.

Örnek kullanım:

    $data = array('name' => 'John', 'email' => 'john@example.com'); 
    $database->insert('users', $data);

### `update($table, $columnPrimary, $id, $data)`
Belirtilen tablodaki bir kaydı günceller.

-   `$table`: Güncellenen kaydın bulunduğu tablo adı.
-   `$columnPrimary`: Güncellenen kaydın benzersiz kimlik adı.
-   `$id`: Güncellenen kaydın benzersiz kimliği (ID).
-   `$data`: Güncellenecek verilerin bir ilişkisel dizi olarak sağlanması.


Örnek kullanım:

    $data = array('name' => 'Jane'); 
    $database->update('users', 1, $data);

### `find($table, $column, $value)`
Belirtilen tabloda bir sütunun değeriyle eşleşen bir kaydı bulur.'
-   `$table`: Arama yapılacak tablo adı.
-   `$column`: Arama yapılacak sütun adı.
-   `$value`: Aranan değer.

Örnek kullanım:

    $user = $database->find('users', 'email', 'john@example.com');
    
    if ($user) {
        // Bulunan kullanıcının bilgilerini kullan
    } else {
        // Kullanıcı bulunamadı
    }
    

### `delete($table, $column, $value)`
Belirtilen tablodan bir kaydı siler.

-   `$table`: Kaydın bulunduğu tablo adı.
-   `$column`: Silinecek kaydı bulmak için kullanılan sütun adı.
-   `$value`: Silinecek kaydı bulmak için kullanılan değer.

Örnek kullanım:

    $database->delete('users', 'id', 1);

### `count($table)`
Belirtilen tablodaki kayıt sayısını döndürür.
`$table`: Kayıt sayısının alınacağı tablo adı.

Örnek kullanım:

    $count = $database->count('users');
    
    echo "Toplam kullanıcı sayısı: " . $count;

### `beginTransaction()`, `commit()`, `rollback()`
Veritabanı işlemleri için transaksiyon yönetimini sağlar.

Örnek kullanım:

    $database->beginTransaction();
    
    try {
        // İşlemleriniz burada yer alır
    
        $database->commit();
    } catch (Exception $e) {
        $database->rollback();
    }
    

### Diğer Yöntemler

`findBy`, `orderBy`, `limit`, `join`, `sum`, `paginate`, `groupBy` ve `bulkInsert` gibi diğer birçok yöntem de `Database` sınıfı tarafından sağlanır. Bu yöntemlerin kullanımını ve işlevlerini daha fazla bilgi için sınıfın kaynak kodunu inceleyebilirsiniz.

## Katkıda Bulunma

Her türlü geri bildirim, öneri veya hata bildirimi için Issues bölümünü kullanabilirsiniz. Pull request'ler de açabilirsiniz.

## Lisans

Bu proje MIT lisansı altında lisanslanmıştır. Daha fazla bilgi için [LICENSE](https://chat.openai.com/LICENSE) dosyasına bakabilirsiniz.
