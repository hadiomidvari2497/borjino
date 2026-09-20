# Borjino UI Preview

This directory contains the build tooling for the static UI preview.

## Reference separation

The Nextable HTML files under `docs/design-reference/nextable-html-source/` are design references only. The Pages workflow never copies that directory into the published artifact.

The preview is rendered from the current Borjino PHP pages and current `assets/` directory on `main`, using a temporary MySQL database during CI.

## Expected URL

`https://hadiomidvari2497.github.io/borjino/`

Enable GitHub Pages with **GitHub Actions** as the publishing source in repository Settings → Pages.

## Scope

This is a static visual preview. GitHub Pages does not execute PHP/MySQL, so real persistence, authentication and business operations remain outside this preview.
