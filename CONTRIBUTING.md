# Contributing to Laravel PWA

Thanks for your interest in contributing! 🧑‍💻

Please refer to the [README.md](./README.md) for complete setup instructions, usage guide, contribution workflow, and examples.

## Quick Steps


We appreciate your interest in contributing to this Laravel PWA project! Whether you're reporting issues, fixing bugs, or adding new features, your help is greatly appreciated.

### Forking and Cloning the Repository

1. Go to the repository page on GitHub.
2. Click the **Fork** button at the top-right corner of the repository page.
3. Clone your forked repository:

   
bash
   git clone https://github.com/your-username/laravel-pwa.git


### Reporting Issues

If you encounter any issues, please check if the issue already exists in the **Issues** section. If not, create a new issue with the following details:
- Steps to reproduce the issue
- Expected and actual behavior
- Laravel version
- Any relevant logs or screenshots

### Submit a Pull Request

When you're ready to contribute, open a pull request describing the changes you’ve made and how they improve the project. Please ensure:
- All commits are squashed into one clean commit.
- The code follows **PSR-12** standards.
- You’ve tested the changes locally.

### Coding Standards

- Follow the [PSR-12](https://www.php-fig.org/psr/psr-12/) PHP coding standards.
- Keep your commit history clean and meaningful.
- Add comments where needed but avoid over-commenting.

## Example Workflow 🌟

Here’s a simple example of how to use this package:

1. Install the package via Composer.
2. Publish the configuration files.
3. Add the @PwaHead directive in your layout file’s <head>.
4. Add the @RegisterServiceWorkerScript directive before the closing </body> tag.
5. Customize the config/pwa.php to fit your project’s needs.
6. Run php artisan erag:pwa-update-manifest to update the manifest file.


Let’s build something amazing together! 🚀
