# Groove Yard

## Objective

The purpose of the site is to allow users to manage their vinyl album collection and help them find other albums sold by other 'nearby' users.

## Execution

Upon registration, users will donate their address, which will be stored with its geographical coordinates. The distance between the various users will be calculated using the coordinates.

The site is equipped with a pre-set list of albums, visible in the Records page. Users can then add them to their inventory, specifying whether they intend to simply add it to their collection, announce that they want to sell it (Sell list) or that they are looking for it (Wish list).

A user will automatically be notified via email when an album in their Wishlist is added to the Sell list by another user.

The search can also be done personally via the Market page, where you can see the list of albums for sale and filter it by distance.

## Unfinished

At the moment there are no real methods of contact between users, apart from giving access to other users' emails, solution that could cause problems given the disclosure of personal data. A chat system could be a solution.

Edit profile form does not ask for security check.

## Info

- EasyAdmin access: /admin
- MAILER_DSN need to be setted in .env.local
- Admin email / password: grooveyard@email.com / admin
- Password base per tutti gli utenti: test

The Inventory entity represents the collections of individual users, it is a user-album link table that adds extra information like conditions and intention. A user can have the same album as different inventory entry (as he can own multiple copies) with different conditions and intentions.

### Custom events

- AddressRegistrationEvent: This event is dispatched when a new address is registered.
- ItemToSellEvent: This event is dispatched when an item (inventory entity) is registrated or edited with intention "to sell".

### Event Subscribers

- GetCoordinateSubscriber: Listen to the AddressRegistrationEvent to get the user address, find his coordinates and pass them into the user info.
- HashUSerPasswordSubscriber: Listen to the prePersist event to hash the user's password when they register.
- UserNotifierSubscriber: Listen to the ItemToSellEvent in order to send a notification email to users who have the same album in their wish list within 50 km of the seller.

### Services

- CoordinateApi: The getCoordinate method takes an instance of the Address entity and returns, by help of the API 'Nominatim', an array with latitude and longitude.
- DistanceCalculator: Contains the getBoundingBox method which takes an instance of the Address entity and a radius in kilometers to returns a bounding box, i.e. a maximum and a minimum in longitude and latitude around the given address of the requested range.

## Known issiue

- Management of certain exceptions is missing, this could lead to errors in case of unexpected data.
- Too much logic in some controllers. the code could be separated better.
