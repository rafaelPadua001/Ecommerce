

@component('mail::message')
# Welcome {{config('app.name')}}

Hello {{$customer_name}},

Welcome to our online store! We are thrilled to have you as our new customer. Here at {{ config('app.name') }}, we are committed to providing an exceptional shopping experience and want to ensure you have a great time with us.

To start exploring our wide selection of products, click the button below:

@component('mail::button', ['url' => $url, 'color' => 'success'])
Explore our products
@endcomponent

Additionally, we want you to know that we are here to assist you whenever you need. If you have any questions about products, shipments, payments, or anything else, please don't hesitate to contact us.

We look forward to helping you find exactly what you're looking for and providing an outstanding shopping experience.

Thank you once again for joining us!
Best Regards,<br>
{{ config('app.name') }} Team
@endcomponent

