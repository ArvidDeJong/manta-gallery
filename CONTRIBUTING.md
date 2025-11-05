# Contributing to Manta Gallery

Bedankt voor je interesse om bij te dragen aan de Manta Gallery module! We waarderen alle bijdragen, of het nu gaat om bugfixes, nieuwe features, documentatie verbeteringen of andere verbeteringen.

## Hoe bij te dragen

### 1. Fork en Clone

1. Fork de repository op GitHub: https://github.com/ArvidDeJong/manta-gallery
2. Clone je fork lokaal:
   ```bash
   git clone https://github.com/jouw-username/manta-gallery.git
   cd manta-gallery
   ```

### 2. Development Setup

1. Installeer dependencies:
   ```bash
   composer install
   ```

2. Zorg ervoor dat je Laravel 12 en PHP 8.2+ gebruikt
3. Installeer de Manta CMS als je de module wilt testen

### 3. Maak je wijzigingen

1. Maak een nieuwe branch voor je feature/bugfix:
   ```bash
   git checkout -b feature/jouw-feature-naam
   ```

2. Maak je wijzigingen
3. Volg de coding standards (PSR-12)
4. Voeg tests toe indien van toepassing

### 4. Testing

Zorg ervoor dat alle tests slagen voordat je een pull request indient:

```bash
composer test
```

### 5. Pull Request

1. Push je branch naar je fork:
   ```bash
   git push origin feature/jouw-feature-naam
   ```

2. Maak een pull request op GitHub
3. Beschrijf duidelijk wat je wijzigingen doen
4. Link naar relevante issues indien van toepassing

## Coding Standards

- Volg PSR-12 coding standards
- Gebruik meaningful variable en function namen
- Schrijf duidelijke commit messages
- Documenteer nieuwe features in de `/docs` directory

## Rapporteer Bugs

Als je een bug vindt:

1. Controleer eerst of er al een issue bestaat
2. Maak een nieuw issue met:
   - Duidelijke beschrijving van het probleem
   - Stappen om het probleem te reproduceren
   - Verwacht vs werkelijk gedrag
   - PHP/Laravel versie informatie

## Feature Requests

Voor nieuwe features:

1. Open eerst een issue om de feature te bespreken
2. Wacht op feedback voordat je begint met implementeren
3. Zorg ervoor dat de feature past bij de doelen van het project

## Code Review Process

Alle pull requests worden gereviewd door de maintainers. We kijken naar:

- Code kwaliteit en standards
- Test coverage
- Documentatie updates
- Backward compatibility

## Vragen?

Als je vragen hebt over het bijdragen, neem dan contact op via:

- Email: info@arvid.nl
- GitHub Issues voor technische vragen

Bedankt voor je bijdrage! 🎉
