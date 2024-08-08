## Authentication

### Register a New Customer ✅

-   **Endpoint**: `POST /api/auth/register`
-   **Description**: Register a new user.
-   **Request Body**:
    -   `name`: User's name
    -   `email`: User's email
    -   `password`: User's password
    -   `role`: User's role (customer)

### Register a New Admin (Admin Only) ✅

-   **Endpoint**: `POST /api/auth/createAdmin`
-   **Description**: Register a new Admin.
-   **Request Body**:
    -   `name`: User's name
    -   `email`: User's email
    -   `password`: User's password
    -   `role`: User's role (admin)

### Register a New RestaurantAdmin (Admin Only) ✅

-   **Endpoint**: `POST /api/auth/createRestaurantAdmin`
-   **Description**: Register a new Restaurant Admin.
-   **Request Body**:
    -   `name`: User's name
    -   `email`: User's email
    -   `password`: User's password
    -   `role`: User's role (RestaurantAdmin)
    -   `restaurant_id`: Restaurant ID

### Login ✅

-   **Endpoint**: `POST /api/auth/login`
-   **Description**: Login an existing user.
-   **Request Body**:
    -   `email`: User's email
    -   `password`: User's password

### Logout ✅

-   **Endpoint**: `GET /api/auth/logout`
-   **Description**: Logout the current user.

## Restaurants

### Get All Restaurants ✅

-   **Endpoint**: `GET /api/restaurants`
-   **Description**: Retrieve all restaurants.

### Get a Single Restaurant by ID ✅

-   **Endpoint**: `GET /api/restaurants/search`
-   **Request Body**:
    -   `id`: Restaurant ID
-   **Description**: Retrieve a single restaurant by ID.

### Search for Restaurants by name or within a radius from location ✅

-   **Endpoint**: `GET /api/restaurants/search`
-   **Request Body**:
    -   `name`: Restaurant name (optional)
    -   `address_id`: Foreign key referencing the addresses table (optional)
    -   `range`: Search radius in kilometers (optional)
-   **Description**: Search for restaurants by name or location.
-   **Examples**:

    -   Retrieve all restaurants withing range 10km from address 5:

        `GET /api/restaurants/search`

        ```json
        {
        	"address_id": "5",
        	"range": "10"
        }
        ```

        Response:

        ```json
        {
        	"restaurants": [
        		{
        			"id": 5,
        			"name": "Pizza Palace",
        			"description": "Classic Italian pizza and pasta",
        			"phone": "111-222-3333",
        			"email": "pizzapalace@example.com",
        			"address_id": 5,
        			"created_at": "2024-08-06T00:53:17.000000Z",
        			"updated_at": "2024-08-06T00:53:17.000000Z"
        		},
        		{
        			"id": 6,
        			"name": "Indian Spice",
        			"description": "Traditional Indian cuisine",
        			"phone": "444-555-6666",
        			"email": "indianspice@example.com",
        			"address_id": 6,
        			"created_at": "2024-08-06T00:53:45.000000Z",
        			"updated_at": "2024-08-06T00:53:45.000000Z"
        		}
        	]
        }
        ```

    -   Search for restaurants by name within range 10km from address 5:

        `GET /api/restaurants/search`

        ```json
        {
        	"name": "pizza",
        	"address_id": "5",
        	"range": "10"
        }
        ```

        Response:

        ```json
        {
        	"restaurants": [
        		{
        			"id": 5,
        			"name": "Pizza Palace",
        			"description": "Classic Italian pizza and pasta",
        			"phone": "111-222-3333",
        			"email": "pizzapalace@example.com",
        			"address_id": 5,
        			"created_at": "2024-08-06T00:53:17.000000Z",
        			"updated_at": "2024-08-06T00:53:17.000000Z"
        		}
        	]
        }
        ```

### Create a New Restaurant (Admin Only) ✅

-   **Endpoint**: `POST /api/restaurants`
-   **Description**: Create a new restaurant.
-   **Request Body**:
    -   `name`: Restaurant's name
    -   `description`: Restaurant's description
    -   `phone`: Restaurant's phone number
    -   `email`: Restaurant's email
    -   `address.country`: Restaurant's address country
    -   `address.city`: Restaurant's address city
    -   `address.street`: Restaurant's address street
    -   `address.latitude`: Restaurant's address latitude
    -   `address.longitude`: Restaurant's address longitude
-   **Authorization**: Admin role required.

## Menus

### Get a Restaurant's Menus ✅

-   **Endpoint**: `GET /api/menus/`
-   **Request Body**:
    -   `restaurant_id`: Foreign key referencing the restaurants table
-   **Description**: Retrieve all menus of a restaurant.

### Get a Single Menu by ID ✅

-   **Endpoint**: `GET /api/menus/:id`
-   **Description**: Retrieve a single menu by ID.

### Create a New Menu (RestaurantAdmin Only) ✅

-   **Endpoint**: `POST /api/menus`
-   **Description**: Create a new menu.
-   **Request Body**:
    -   `restaurant_id`: Foreign key referencing the restaurants table
-   **Authorization**: User with RestaurantAdmin role and restaurant_id = :restaurant_id required.

### Update a Menu's name/description by ID (RestaurantAdmin Only) ✅

-   **Endpoint**: `PUT /api/menus/:id`
-   **Description**: Update a menu's details.
-   **Request Body**:
    -   `restaurant_id`: Foreign key referencing the restaurants table
    -   `name`: Updated menu name (optional)
    -   `description`: Updated menu description (optional)
-   **Authorization**: User with RestaurantAdmin role and restaurant_id = :restaurant_id required.

### Delete a Menu by ID (RestaurantAdmin and Admin Only) ✅

-   **Endpoint**: `DELETE /api/menus/:id`
-   **Description**: Delete a menu.
-   **Authorization**: User with Admin role or RestaurantAdmin role and restaurant_id = :restaurant_id required.

## Menu Items

### Get All Menu Items of a menu ✅

-   **Endpoint**: `GET /api/menu-items/menu/{menu_id}`
-   **Description**: Retrieve all menu items.

### Get a Single Menu Item by ID ✅

-   **Endpoint**: `GET /api/menu-items/:id`
-   **Description**: Retrieve a single menu item by ID.

### Create a New Menu Item (RestaurantAdmin Only) ✅

-   **Endpoint**: `POST /api/menu-items`
-   **Description**: Create a new menu item.
-   **Request Body**:
    -   `menu_id`: Foreign key referencing the menus table
    -   `name`: Menu item name
    -   `description`: Menu item description
    -   `price`: Menu item price
-   **Authorization**: RestaurantAdmin role required.

### Update a Menu Item by ID (RestaurantAdmin Only) ✅

-   **Endpoint**: `PUT /api/menu-items/:id`
-   **Description**: Update a menu item's details.
-   **Request Body**:
    -   `menu_id`: Foreign key referencing the menus table
    -   `name`: Menu item name
    -   `description`: Menu item description
    -   `price`: Menu item price
-   **Authorization**: Admin role required.

### Delete a Menu Item by ID (Admin and RestaurantAdmin Only) ✅

-   **Endpoint**: `DELETE /api/menu-items/:id`
-   **Description**: Delete a menu item.
-   **Authorization**: RestaurantAdmin or Admin role required.
