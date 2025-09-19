<?php
class Cart
{
    private $sessionKey = 'cart';
    private $koneksi;

    public function __construct($koneksi)
    {
        $this->koneksi = $koneksi;
        if (!isset($_SESSION[$this->sessionKey])) {
            $_SESSION[$this->sessionKey] = [];
        }
    }

    public function initDB($id_transaksi)
    {
        if (empty($_SESSION[$this->sessionKey])) {
            $stmt = $this->koneksi->prepare("SELECT p.*, mn.*, t.total FROM pesanan p JOIN menu mn ON p.id_menu=mn.id JOIN transaksi t ON t.id=p.id_transaksi WHERE t.id=?");
            $stmt->bind_param('i', $id_transaksi);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $_SESSION[$this->sessionKey][] = [
                    'id_menu'   => $row['id_menu'],
                    'nama_menu' => $row['nama_menu'],
                    'foto'      => $row['foto'],
                    'harga'     => $row['harga'],
                    'jumlah'    => $row['jumlah'],
                    'subtotal'  => $row['harga'] * $row['jumlah']
                ];
            }
        }
    }

    public function getItems()
    {
        return $_SESSION[$this->sessionKey];
    }

    // Tambah ke keranjang
    public function tambahKeranjang($id_menu, $jumlah, $koneksi)
    {

        if ($jumlah > 0) {
            // Ambil data menu dari DB
            $stmt = $koneksi->prepare("SELECT * FROM menu WHERE id = ?");
            $stmt->bind_param("i", $id_menu);
            $stmt->execute();
            $menu = $stmt->get_result()->fetch_assoc();

            if ($menu) {
                // Cek apakah item sudah ada di keranjang
                if (!empty($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as &$c) {
                        if ($c['id_menu'] == $id_menu) {
                            $c['jumlah'] += $jumlah;
                            $c['subtotal'] = $c['harga'] * $c['jumlah'];
                            return;
                        }
                    }
                }

                // Kalau belum ada, tambah baru
                $_SESSION['cart'][] = [
                    'id_menu'   => $menu['id'],
                    'nama_menu' => $menu['nama_menu'],
                    'foto'      => $menu['foto'],
                    'harga'     => $menu['harga'],
                    'jumlah'    => $jumlah,
                    'subtotal'  => $menu['harga'] * $jumlah
                ];
            }
        }
    }

    public function tambah($id_menu)
    {
        foreach ($_SESSION[$this->sessionKey] as &$c) {
            if ($c['id_menu'] == $id_menu) {
                $c['jumlah']++;
                $c['subtotal'] = $c['harga'] * $c['jumlah'];
                return;
            }
        }
    }

    public function kurang($id_menu)
    {
        foreach ($_SESSION[$this->sessionKey] as &$c) {
            if ($c['id_menu'] == $id_menu && $c['jumlah'] > 0) {
                $c['jumlah']--;
                $c['subtotal'] = $c['harga'] * $c['jumlah'];
                return;
            }
        }
    }

    public function hapus($id_menu)
    {
        foreach ($_SESSION[$this->sessionKey] as $key => $c) {
            if ($c['id_menu'] == $id_menu) {
                unset($_SESSION[$this->sessionKey][$key]);
                return;
            }
        }
    }

    public function getTotalHarga(){
        $total = 0;
        foreach ($_SESSION[$this->sessionKey] as $c) {
            $total += $c['subtotal'];
        }
        return $total;
    }

    public function pesan($id_transaksi){
        $stmt = $this->koneksi->prepare("delete from pesanan where id_transaksi=?");
        $stmt->bind_param('i', $id_transaksi);
        if($stmt->execute()){
            foreach ($_SESSION[$this->sessionKey] as $c) {
                $stmt = $this->koneksi->prepare("insert into pesanan (id_transaksi, id_menu, jumlah, harga, subtotal) values (?,?,?,?,?)");
                $stmt->bind_param('iiiii', $id_transaksi, $c['id_menu'], $c['jumlah'], $c['harga'], $c['subtotal']);
                $stmt->execute();
            }
            // update total di transaksi
            $total = $this->getTotalHarga();
            $stmt = $this->koneksi->prepare("update transaksi set total=? where id=?");
            $stmt->bind_param('di', $total, $id_transaksi);
            $stmt->execute();
            $stmt->close();
            return true;
        }
    }

    public function clear()
    {
        $_SESSION[$this->sessionKey] = [];
    }
}
