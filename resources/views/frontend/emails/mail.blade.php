<form action="{{ action('Frontend\OrderController@mail') }}" method="post">
    <label>Email</label>
    <input type="text" name="email" required="required" />
    <label>Name</label>
    <input type="text" name="name" required="required" />
    <button type="submit">Mail</button>
    {{ csrf_field() }}
</form>