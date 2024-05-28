
# Laravel OpenRouter

<br />

[![Latest Version on Packagist](https://img.shields.io/badge/packagist-v1.0-blue)](https://packagist.org/packages/moe-mizrak/laravel-openrouter)
[![](https://dcbadge.vercel.app/api/server/KBPhAPEJNj?style=flat)](https://discord.gg/3TbKAakhGb)
<br />

This Laravel package provides an easy-to-use interface for integrating **[OpenRouter](https://openrouter.ai/)** into your Laravel applications. OpenRouter is a unified interface for Large Language Models (LLMs) that allows you to interact with various **[AI models](https://openrouter.ai/docs#models)** through a single API.

## Table of Contents

- [🤖 Requirements](#-requirements)
- [🏁 Get Started](#-get-started)
- [⚙️ Configuration](#-configuration)
- [🎨 Usage](#-usage)
- [📜 License](#-license)

## 🤖 Requirements

- **PHP 8.1** or **higher**

## 🏁 Get Started

You can **install** the package via composer:

```bash
composer require moe-mizrak/laravel-openrouter
```
You can **publish** the **config file** with:

```bash
php artisan vendor:publish --provider="MoeMizrak\LaravelOpenRouter\OpenRouterServiceProvider"
```
This is the contents of the **published config file**:

```php
return [
    'api_endpoint' => env('OPENROUTER_API_ENDPOINT', 'https://openrouter.ai/api/v1/'),
    'api_key'      => env('OPENROUTER_API_KEY'),
];
```

## ⚙️ Configuration
After publishing the package configuration file, you'll need to add the following environment variables to your **.env** file:

```env
OPENROUTER_API_ENDPOINT=https://openrouter.ai/api/v1/
OPENROUTER_API_KEY=your_api_key
```

- OPENROUTER_API_ENDPOINT: The endpoint URL for the **OpenRouter API** (default: https://openrouter.ai/api/v1/).
- OPENROUTER_API_KEY: Your **API key** for accessing the OpenRouter API. You can obtain this key from the [OpenRouter dashboard](https://openrouter.ai/keys).

## 🎨 Usage

// TODO: Add usage instructions and examples

## 📜 License
Laravel OpenRouter is an open-sourced software licensed under the **[MIT license](LICENSE)**.