@extends('frontend.layouts.master')
@section('title','Cart Page')
@section('main-content')
<!-- Breadcrumbs -->
<div class="breadcrumbs">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="bread-inner">
					<ul class="bread-list">
						<li><a href="{{('home')}}">Home<i class="ti-arrow-right"></i></a></li>
						<li class="active"><a href="">Cart</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Breadcrumbs -->

<!-- Shopping Cart -->
<div class="shopping-cart section">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<!-- Shopping Summery -->
				<table class="table shopping-summery">
					<thead>
						<tr class="main-hading">
							<th>PRODUCT</th>
							<th>NAME</th>
							<th class="text-center">UNIT PRICE</th>
							<th class="text-center">QUANTITY</th>
							<th class="text-center">TOTAL</th>
							<th class="text-center"><i class="ti-trash remove-icon"></i></th>
						</tr>
					</thead>
					<tbody id="cart_item_list">
						<form action="{{route('cart.update')}}" method="POST">
							@csrf
							@if(Helper::getAllProductFromCart())
							@foreach(Helper::getAllProductFromCart() as $key=>$cart)
							<tr>
								@php
								$photo=explode(',',$cart->product['photo']);
								@endphp
								<td class="image" data-title="No"><img src="{{$photo[0]}}" alt="{{$photo[0]}}"></td>
								<td class="product-des" data-title="Description">
									<p class="product-name"><a href="{{route('product-detail',$cart->product['slug'])}}" target="_blank">{{$cart->product['title']}}</a></p>
									<p class="product-des">{!!($cart['summary']) !!}</p>
								</td>
								<td class="price" data-title="Price"><span>Rp{{number_format($cart['amount'],2)}}</span></td>
								<td class="qty" data-title="Qty"><!-- Input Order -->
									<div class="input-group">
										<div class="button minus">
											<button type="button" class="btn btn-primary btn-number" disabled="disabled" data-type="minus" data-field="quant[{{$key}}]">
												<i class="ti-minus"></i>
											</button>
										</div>
										<input type="text" name="quant[{{$key}}]" class="input-number" data-min="1" data-max="100" value="{{$cart->quantity}}">
										<input type="hidden" name="qty_id[]" value="{{$cart->id}}">
										<div class="button plus">
											<button type="button" class="btn btn-primary btn-number" data-type="plus" data-field="quant[{{$key}}]">
												<i class="ti-plus"></i>
											</button>
										</div>
									</div>
									<!--/ End Input Order -->
								</td>
								<td class="total-amount cart_single_price" data-title="Total"><span class="money">Rp{{$cart['price']}}</span></td>

								<td class="action" data-title="Remove"><a href="{{route('cart-delete',$cart->id)}}"><i class="ti-trash remove-icon"></i></a></td>
							</tr>
							@endforeach
							<track>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<!-- <td class="float-right">
								<button class="btn float-right" type="submit">Update</button>
							</td> -->
							</track>
							@else
							<tr>
								<td class="text-center">
									There are no any carts available. <a href="{{route('product-grids')}}" style="color:blue;">Continue shopping</a>

								</td>
							</tr>
							@endif

						</form>
					</tbody>
				</table>
				<!--/ End Shopping Summery -->
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<!-- Total Amount -->
				<div class="total-amount">
					<div class="row">
						<div class="col-lg-8 col-md-5 col-12">
							<div class="left">
								<!-- <div class="coupon">
									<form action="{{route('coupon-store')}}" method="POST">
										@csrf
										<input name="code" placeholder="Enter Valid Coupon">
										<button class="btn">Apply Coupon</button>
									</form>
								</div> -->
								{{-- <div class="checkbox">`
										@php
											$shipping=DB::table('shippings')->where('status','active')->limit(1)->get();
										@endphp
										<label class="checkbox-inline" for="2"><input name="news" id="2" type="checkbox" onchange="showMe('shipping');"> Shipping</label>
									</div> --}}
							</div>
						</div>
						<div class="col-lg-4 col-md-7 col-12">
							<div class="right">
								<ul>
									<li class="order_subtotal" data-price="{{Helper::totalCartPrice()}}">Cart Subtotal<span>Rp{{number_format(Helper::totalCartPrice(),2)}}</span></li>

									<!-- @if(session()->has('coupon'))
									<li class="coupon_price" data-price="{{Session::get('coupon')['value']}}">You Save<span>${{number_format(Session::get('coupon')['value'],2)}}</span></li>
									@endif -->

									<!-- Total Bayar -->
									<li class="last" id="order_total_price">You Pay<span>Rp{{number_format(Helper::totalCartPrice(),2)}}</span></li>
								</ul>
								<div class="button5">
								<a href="{{route('checkout')}}" class="btn" id="checkout_btn" disabled>Checkout</a>
									<a href="{{route('product-grids')}}" class="btn">Continue shopping</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--/ End Total Amount -->
			</div>
		</div>
	</div>
</div>
<!--/ End Shopping Cart -->

<!-- Address Modal -->
<div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addressModalLabel">Enter Delivery Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="delivery_address" placeholder="Enter delivery address" class="form-control">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="calculateShipping()">Save Address</button>
            </div>
        </div>
    </div>
</div>

<!-- End Address Modal -->

@endsection
@push('styles')
<style>
	li.shipping {
		display: inline-flex;
		width: 100%;
		font-size: 14px;
	}

	li.shipping .input-group-icon {
		width: 100%;
		margin-left: 10px;
	}

	.input-group-icon .icon {
		position: absolute;
		left: 20px;
		top: 0;
		line-height: 40px;
		z-index: 3;
	}

	.form-select {
		height: 30px;
		width: 100%;
	}

	.form-select .nice-select {
		border: none;
		border-radius: 0px;
		height: 40px;
		background: #f6f6f6 !important;
		padding-left: 45px;
		padding-right: 40px;
		width: 100%;
	}

	.list li {
		margin-bottom: 0 !important;
	}

	.list li:hover {
		background: #F7941D !important;
		color: white !important;
	}

	.form-select .nice-select::after {
		top: 14px;
	}
</style>
@endpush

@push('scripts')
<script src="{{asset('frontend/js/nice-select/js/jquery.nice-select.min.js')}}"></script>
<script src="{{ asset('frontend/js/select2/js/select2.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $("select.select2").select2();
        $('select.nice-select').niceSelect();
    });

    function calculateShipping() {
        const address = document.getElementById('delivery_address').value;
        const sellerLocation = {lat: -6.1751, lng: 106.8650}; // Example seller location (Jakarta)

        if (address) {
            fetch(`https://api.positionstack.com/v1/forward?access_key=YOUR_ACCESS_KEY&query=${address}`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.data && data.data.length > 0) {
                        const buyerLocation = {
                            lat: data.data[0].latitude,
                            lng: data.data[0].longitude
                        };
                        const distance = calculateDistance(sellerLocation, buyerLocation);
                        let shippingCost = 0;
                        
                        if (distance > 10) {
                            shippingCost = (distance * 500).toFixed(2);
                        }

                        $('#shipping_info').html(`
                            Shipping Cost: <span id="shipping_cost">$${shippingCost}</span>
                            <button class="btn btn-link" onclick="editAddress()">Edit Address</button>
                        `);

                        const subtotal = parseFloat($('.order_subtotal').data('price'));
                        const coupon = parseFloat($('.coupon_price').data('price')) || 0;
                        const total = (subtotal + parseFloat(shippingCost) - coupon).toFixed(2);
                        $('#order_total_price span').text(`$${total}`);
                        $('#checkout_btn').prop('disabled', false); // Enable Checkout Button
                        $('#addressModal').modal('hide');
                    } else {
                        alert("Invalid address.");
                    }
                });
        } else {
            alert("Please enter an address.");
        }
    }

    function editAddress() {
        $('#addressModal').modal('show');
    }

    function calculateDistance(loc1, loc2) {
        const R = 6371; // Radius of the earth in km
        const dLat = deg2rad(loc2.lat - loc1.lat);
        const dLon = deg2rad(loc2.lng - loc1.lng);
        const a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(deg2rad(loc1.lat)) * Math.cos(deg2rad(loc2.lat)) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        const distance = R * c; // Distance in km
        return distance;
    }

    function deg2rad(deg) {
        return deg * (Math.PI / 180);
    }
</script>

@endpush