<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        .total-red {
            background-color: red !important;
            color: white; /* Numbers will remain readable */
        }
        .total-green {
            background-color: green !important;
            color: white;
        }
        .create-btn {
            font-size: 1.5em;
            background-color: yellow;
            color: black;
            font-weight: bold;
            border: none;
            padding: 10px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .create-btn:hover {
            background-color: orange; /* Change when hovered */
            transform: scale(1.1); /* Slightly enlarge the button */
        }
        .update-btn {
            display: none;
            font-size: 1.5em;
            background-color: #ffc107;
            color: black;
            font-weight: bold;
            border: none;
            padding: 10px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .update-btn:hover {
            background-color: #e0a800; /* Darker yellow on hover */
        }
    </style>
</head>
<body class="bg-dark text-white">

    <div class="container my-5">
        <!-- Create and Update Product Form -->
        <form id="product-form" action="{{ route('product.store') }}" method="POST" class="row g-3">
            @csrf
            <input type="hidden" id="product-id" name="id">
            
            <div class="col-md-3">
                <input type="text" name="title" id="title" class="form-control" placeholder="Title">
            </div>
            <div class="col-md-2">
                <input type="number" name="price" id="price" class="form-control" placeholder="Price">
            </div>
            <div class="col-md-2">
                <input type="number" name="taxes" id="taxes" class="form-control" placeholder="Taxes">
            </div>
            <div class="col-md-2">
                <input type="number" name="ads" id="ads" class="form-control" placeholder="Ads">
            </div>
            <div class="col-md-2">
                <input type="number" name="discount" id="discount" class="form-control" placeholder="Discount">
            </div>
            <div class="col-md-3">
                <input type="text" id="total" class="form-control total-red" placeholder="Total" readonly>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100 create-btn" id="create-btn">Create</button>
                <button type="submit" class="btn btn-warning w-100 update-btn" id="update-btn">Update</button>
            </div>
        </form>

        <hr class="text-white my-4">

        <!-- Products Table -->
        <table class="table table-bordered table-dark mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Taxes</th>
                    <th>Ads</th>
                    <th>Discount</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->title }}</td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->taxes }}</td>
                        <td>{{ $product->ads }}</td>
                        <td>{{ $product->discount }}</td>
                        <td>{{ $product->price + $product->taxes + $product->ads - $product->discount }}</td>
                        <td>
                            <!-- Update Button -->
                            <button class="btn btn-warning btn-sm edit-btn"
                                data-id="{{ $product->id }}"
                                data-title="{{ $product->title }}"
                                data-price="{{ $product->price }}"
                                data-taxes="{{ $product->taxes }}"
                                data-ads="{{ $product->ads }}"
                                data-discount="{{ $product->discount }}">
                                Update
                            </button>

                            <!-- Delete Form -->
                            <form action="{{ route('product.destroy', $product->id) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            // Calculate total on input change
            $('#price, #taxes, #ads, #discount').on('input', function () {
                let price = parseFloat($('#price').val()) || 0;
                let taxes = parseFloat($('#taxes').val()) || 0;
                let ads = parseFloat($('#ads').val()) || 0;
                let discount = parseFloat($('#discount').val()) || 0;

                let total = price + taxes + ads - discount;
                $('#total').val(total.toFixed(2));

                // Change total background color based on price
                if (price > 0) {
                    $('#total').removeClass('total-red').addClass('total-green');
                } else {
                    $('#total').removeClass('total-green').addClass('total-red');
                }
            });

            // Populate the form for update
            $('.edit-btn').on('click', function () {
                const id = $(this).data('id');
                const title = $(this).data('title');
                const price = $(this).data('price');
                const taxes = $(this).data('taxes');
                const ads = $(this).data('ads');
                const discount = $(this).data('discount');

                $('#product-id').val(id);
                $('#title').val(title);
                $('#price').val(price);
                $('#taxes').val(taxes);
                $('#ads').val(ads);
                $('#discount').val(discount);

                // Show Update button and hide Save button
                $('#create-btn').hide();
                $('#update-btn').show();

                // Update the form action to the update route
                $('#product-form').attr('action', `/product/${id}`);
                $('#product-form').append('<input type="hidden" name="_method" value="PUT">');
            });

            // Reset the form when Save button is clicked
            $('#create-btn').on('click', function () {
                $('#product-form').attr('action', '{{ route('product.store') }}');
                $('#product-form').find('input[name="_method"]').remove();
                $('#product-id').val('');
            });

            // Confirmation message for delete
            $('.delete-form').on('submit', function (e) {
                if (!confirm('هل انت متأكد انك تريد حذف المنتج؟')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>