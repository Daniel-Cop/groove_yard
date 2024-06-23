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

## Info

Admin email / password: grooveyard@email.com / admin

## Known issiue

- Management of certain exceptions is missing, this could lead to errors in case of unexpected data.
- Too much logic in some controllers. the code could be separated better.
