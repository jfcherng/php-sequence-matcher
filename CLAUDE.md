# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
composer test      # run PHPUnit tests
composer analyze   # run Phan static analysis + phpcs linting
composer fix       # auto-format with php-cs-fixer
```

## Code Style

- PSR-12 plus extended php-cs-fixer rules (see `.php-cs-fixer.dist.php`)
- Short array syntax `[]`, not `array()`
- Trailing commas in multiline arrays and argument lists
- Risky php-cs-fixer rules are enabled (strict types, native function invocation, etc.)
- Run `composer fix` to auto-format; do not hand-format to match the ruleset

## Testing

- Test classes must declare `@covers` annotations (enforced by php-cs-fixer rule `php_unit_test_class_requires_covers`)
- Run single test: `vendor/bin/phpunit --filter TestName`

## Git Workflow

- Branch naming: `feature/`, `fix/`, or `chore/` prefixes
- Commit style: conventional commits (`feat`, `fix`, `refactor`, `chore`, `perf`, `ci`, `docs`, `test`)

## Approach

- Think before acting. Read existing files before writing code.
- Be concise in output but thorough in reasoning.
- Prefer editing over rewriting whole files.
- Do not re-read files you have already read unless the file may have changed.
- Test your code before declaring done.
- No sycophantic openers or closing fluff.
- Keep solutions simple and direct.
- User instructions always override this file.
