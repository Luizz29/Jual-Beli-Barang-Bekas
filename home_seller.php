<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Upload Produk - Second Change</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="style.css">
    <style>
        .preview-img {
            max-width: 20%;
            height: auto;
            margin-top: 20px;
            
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Upload Produk</h1>
        <form id="productForm" action="upload_product.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="productName" class="form-label">Nama Produk</label>
                <input type="text" class="form-control" id="productName" name="productName" placeholder="Masukkan nama produk" required>
            </div>
            <div class="mb-3">
                <label for="productPrice" class="form-label">Harga Produk</label>
                <input type="text" class="form-control" id="productPrice" name="productPrice" placeholder="Masukkan harga produk" required>
            </div>
            <div class="mb-3">
                <label for="productImage" class="form-label">Pilih Gambar Produk</label>
                <input class="form-control" type="file" id="productImage" name="productImage" accept="image/*" required>
            </div>
            <div class="mb-3">
                <img id="imagePreview" class="preview-img" src="" alt="Preview Gambar Produk">
            </div>
            <button type="submit" class="btn btn-primary">Upload Produk</button>
        </form>
    </div>

    <script>
        // Preview gambar
        const productImageInput = document.getElementById('productImage');
        const imagePreview = document.getElementById('imagePreview');

        productImageInput.addEventListener('change', function() {
            const file = this.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                }

                reader.readAsDataURL(file);
            } else {
                imagePreview.src = "";
            }
        });

        // SweetAlert untuk konfirmasi upload produk
        document.getElementById('productForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Mencegah submit form langsung

            const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger me-2' // Tambahkan class me-2 untuk jarak
    },
    buttonsStyling: false
});


            swalWithBootstrapButtons.fire({
                title: 'Apakah anda yakin?',
                text: "Produk akan diupload!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, upload!',
                cancelButtonText: 'Tidak, batalkan!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    swalWithBootstrapButtons.fire(
                        'Berhasil!',
                        'Produk anda telah diupload.',
                        'success'
                    );
                    // Kirim form setelah konfirmasi
                    e.target.submit();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire(
                        'Dibatalkan',
                        'Pengunggahan produk dibatalkan :)',
                        'error'
                    );
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
