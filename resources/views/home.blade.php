<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Routes List</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
<h1>Available Routes List</h1>
<table>
    <thead>
    <tr>
        <th>Method</th>
        <th>URI</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>GET</td>
        <td>api/orders</td>
        <td>Return all Orders</td>
    </tr>
    <tr>
        <td>POST</td>
        <td>api/orders</td>
        <td>Save an Order in database</td>
    </tr>
    <tr>
        <td>PUT</td>
        <td>api/order/{id}</td>
        <td>Update Order in database</td>
    </tr>
    <tr>
        <td>GET</td>
        <td>api/get-order/{id}</td>
        <td>Get an Order</td>
    </tr>
    <tr>
        <td>DELETE</td>
        <td>api/order/{id}</td>
        <td>Delete an Order in database</td>
    </tr>
    <tr>
        <td>GET</td>
        <td>api/schedule</td>
        <td>Sort Orders for schedule page</td>
    </tr>
    <tr>
        <td>GET</td>
        <td>api/products</td>
        <td>Get all Products</td>
    </tr>
    </tbody>
</table>
<h2>All of these Routes needs an API_KEY</h2>
</body>
</html>
