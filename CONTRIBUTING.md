# Contributing to FlowFinder

[Guide de contribution en français](./CONTRIBUTING.fr.md)

Thank you for your interest in contributing to FlowFinder! We welcome contributions from the community and are excited to work together to improve this project.

## How to Contribute

We are still in the early stages of development, so there are several ways you can help us!

### 1. **Bug Reports**
If you find any bugs or issues, please report them by creating a GitHub issue. Be as detailed as possible when describing the problem.

### 2. **Feature Requests**
If you have an idea for a new feature (such as a plugin system for authentication sources), feel free to suggest it! Create an issue with the label "enhancement" and provide a clear description of the feature.

### 3. **Pull Requests**
We welcome pull requests! Here are some guidelines:
- **Fork** the repository and create your branch (`git checkout -b feature/your-feature`).
- **Commit** your changes with clear, concise messages explaining what you've done.
- **Push** your changes to your forked repository and create a pull request to the `main` branch.
- Make sure your code follows existing code style conventions.
- Add tests if applicable and ensure all tests pass.

### 4. **Development Priorities**
We are currently focused on implementing the following features:
- **Plugin System**: We plan to add a plugin system to support multiple authentication sources. This will replace the hardcoded `admin/admin` credentials.
- **Sensitive Data Exclusion for rrweb**: We are building a user interface to allow users to fine-tune rrweb and exclude sensitive data from session recordings.

If you want to help with any of these tasks, please let us know!

### 5. **Setting Up the Development Environment**
- Fork the repository and clone it locally.
- Install the dependencies using Composer (`composer install`).
- Set up your database using the provided SQL schema files (`_database/_create_schema_for_dev.sql` and `_database/schema_version_00001.sql`).
- Ensure Apache is configured with the correct `DocumentRoot` and `.htaccess`.

### 6. **Code of Conduct**
Please be respectful and considerate of others. We value a positive and inclusive environment for all contributors.

