# Contributing to Laravel OpenRouter

Thank you for considering contributing to Laravel OpenRouter! Your contributions help make this package better for everyone.

## Getting Started

1. Fork the repository
2. Clone your fork locally
3. Create a new branch for your changes

## Development Setup

1. Install dependencies:
   ```bash
   composer install
   ```

2. Run tests to ensure everything is working:
   ```bash
   composer test
   ```

## Commit Messages

Use **Conventional Commits** for all commit messages. This helps maintain a clear and consistent commit history.

### Types

- `feat`: A new feature (e.g., `feat: add new UsageData fields`)
- `fix`: A bug fix (e.g., `fix: correct response parsing for streaming`)
- `docs`: Documentation changes (e.g., `docs: update README with new examples`)
- `test`: Adding or updating tests (e.g., `test: add tests for CostResponseData`)
- `refactor`: Code changes that neither fix bugs nor add features (e.g., `refactor: simplify API request handling`)
- `chore`: Maintenance tasks (e.g., `chore: update dependencies`)
- `style`: Code style changes (formatting, semicolons, etc.)

## Testing Guidelines

### Adding Tests

- **Ensure tests are added or updated** for any code changes
- Tests should be placed in the `tests/` directory
- Follow the existing testing patterns in `OpenRouterAPITest.php`

### Verify Before Mocking

Before mocking API responses in tests:

1. **Make a real request first** (e.g., to a free model on OpenRouter)
2. **Confirm the response structure** matches your expectations
3. **Then create your mock** based on the actual response

This ensures your mocks accurately reflect real API behavior and prevents false positives in tests.

### Running Tests

```bash
# Run all tests
composer test

# Or directly with PHPUnit
vendor/bin/phpunit
```

## Pull Request Guidelines

### Keep PRs Focused

- **Each PR should address a single concern**
- Do not include unrelated fixes or features
- Open separate PRs for different issues or features

### Before Submitting

1. Ensure all tests pass locally
2. Update documentation if your changes affect:
   - Usage examples
   - Configuration options
   - Public API behavior
3. Fill out the PR template completely
4. Reference any related issues

## Naming Conventions

### Follow Existing Patterns

- Review similar classes in the codebase for consistency
- Check the `src/DTO/` directory for Data Transfer Object naming patterns
- Check the `src/Types/` directory for enum/type naming patterns

### API Field Alignment

Request/response field names should align with:

1. **OpenRouter API documentation**: [https://openrouter.ai/docs/quickstart](https://openrouter.ai/docs/quickstart)
2. **Package's existing naming conventions**

When in doubt, prioritize consistency with:
- Existing DTOs in the package
- Official OpenRouter API documentation

## Documentation

### Update README

Update the README.md if your changes affect:

- New features or capabilities
- Configuration options
- Usage examples
- Public API changes

### Code Comments

- Add comments for complex logic
- Use PHPDoc blocks for public methods and classes
- Follow existing comment style in the codebase

## Security

- **Never commit or expose API keys** or other sensitive credentials
- Use environment variables for all sensitive configuration
- Review your changes before committing to ensure no secrets are included

---

Thank you for contributing to Laravel OpenRouter! 🚀
