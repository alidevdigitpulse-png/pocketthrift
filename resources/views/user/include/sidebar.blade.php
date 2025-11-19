<a href="{{ route('user.dashboard') }}" class="{{ currentSelectedURL('user.dashboard') ? 'active' : '' }}"><i
        class="fas fa-th"></i>
    Dashboard</a>

<a href="{{ route('user.orders') }}" class="{{ currentSelectedURL('user.orders') ? 'active' : '' }}"><i
        class="fa fa-cart-arrow-down"></i> Order
    History</a>

<a href="{{ route('user.wishlist') }}" class="{{ currentSelectedURL('user.wishlist') ? 'active' : '' }}"><i
        class="fa fa-heart"></i> My
    Wishlist</a>
<a href="{{ route('user.settings') }}" class="{{ currentSelectedURL('user.settings') ? 'active' : '' }}"><i
        class="fa fa-user"></i> Account
    Details</a>
<form class="d-none" method="POST" id="logout" action="{{ route('logout') }}">
    @csrf
</form>
<a href="javascript:void(0);" class="{{ currentSelectedURL('logout') ? 'active' : '' }}"
    onclick="document.getElementById('logout').submit();"><i class="fas fa-arrow-circle-left"></i>
    Logout</a>
