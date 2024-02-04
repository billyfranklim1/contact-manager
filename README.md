# Contact Manager 📇

![Contact Manager](public/capture.png)
## About the Project

The **Contact Manager** is a Laravel-based web application designed for efficient and intuitive contact management. With CRUD (Create, Read, Update, Delete) functionalities, this project offers a user-friendly interface to add, view, edit, and delete contacts.

### Features

- **Contact Listing**: Easily view all registered contacts. 📋
- **Adding New Contacts**: Register new contacts with details like name, contact, and email. ➕
- **Viewing Contacts**: Access each contact's details. 👀
- **Editing Contacts**: Update existing contact information. ✏️
- **Deleting Contacts**: Safely remove contacts with soft deletion. 🗑️

### Technical Considerations

- **Validation**: Name (>5 characters), Contact (9 digits), and Email (valid format) with uniqueness.
- **Authentication**: Some functionalities are restricted to authenticated users.
- **Tests**: Test coverage for form validation.
- **Architecture**: Implementation of Services and Repository layers for clean and maintainable architecture.

## Getting Started

To set up and run the project locally, follow these steps:

### Prerequisites

- PHP 8.1
- Composer
- Laravel 10

### Installation

1. Clone the repository:
   ```sh
   git clone https://github.com/billyfranklim1/contact-manager.git
   ```
2. Install PHP dependencies:
   ```sh
   composer install
   ```
3. Set up the environment by copying `.env.example` to `.env` and adjusting the environment variables as needed.
4. Generate a key for the application:
   ```sh
   php artisan key:generate
   ```
5. Run migrations to create the database structure:
   ```sh
   php artisan migrate
   ```
6. (Optional) Populate the database with test data:
   ```sh
   php artisan db:seed
   ```

## Usage

After setup, you can start the Laravel development server:

```sh
php artisan serve
```

Access the application at `http://localhost:8000`.

## Tests

Run the tests to ensure everything is working as expected:

```sh
php artisan test
```

## Contributions

Contributions are what make the open-source community an amazing place to learn, inspire, and create. Any contributions you make will be **greatly appreciated**.

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## This project supports multiple languages:
- [English](README.md)
- [Português](README.pt.md)

## Contact

My Portfolio: [billy.dev.dev](https://billy.dev.dev)

My LinkedIn: [Billy Franklim](https://www.linkedin.com/in/billyfranklim/)

Email: [billyfranklim@gmail.com](mailto:billyfranklim@gmail.com)

Project Link: [https://github.com/billyfranklim1/contact-manager](https://github.com/billyfranklim1/contact-manager)
