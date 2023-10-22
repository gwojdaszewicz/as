# Zadanie rekrutacyjne

Utworzenie prostej strony z tabelą rekordów zapisanych w bazie danych.

| Lp. | Nr rejestracyjny | Marka    | Model   | Typ pojazdu | Data utworzenia    | Data modyfikacji | Akcje   |
|-----|------------------|----------|---------|-------------|--------------------|------------------|---------|
| 1   | DW22233          | Mercedes | MP-4    | ciężarowy   | 2020-05-05 12:25   | 2021-06-07 15:05 | (e) (d) |
| 2   | BORQ4500         | MAN      | TGE     | bus         | 2021-06-06 14:01   | 2021-06-06 14:01 | (e) (d) |
| 3   | DW33445          | Toyota   | Corolla | osobowy     | 2021-07-05 10:25   | 2021-08-07 12:05 | (e) (d) |

Tabelka powinna zawierać kolumny:
* Lp.
* Numer rejestracyjny pojazdu
* Marka
* Model
* Typ pojazdu (osobowy, bus, ciężarowy)
* Data utworzenia
* Data modyfikacji
* Akcje (bez sortowania)

## Wymagania:

1. Numer rejestracyjny wielkimi literami.
2. Data w formacie: `YYYY-MM-DD HH:MM`.
3. Kolumna akcje zawierać ma dwie ikony akcji: edycja + usuwanie.
4. Do tabeli, dodawania i edycji rekordów należy skorzystać z Vue (wraz z biblioteką
   Vuetify - https://v2.vuetifyjs.com/en/components/data-tables/), dane wprowadzamy w popupie i zapisujemy)
5. W naszej firmie korzystamy z Composera (PHP), Yarn, Axios, webpack (JS), SASS (css).
6. Tabela powinna uwzględniać sortowanie oraz paginację rekordów (po stronie frontu).
7. Wymagane jest podejście DDD (bez użycia CQRS - struktura katalogów niżej).
8. Kod umieść na GitHubie jako **repozytorium publiczne**. Nie twórz fork'a z naszego repozytorium. Pobierz nasz kod, zaincjalizuj tam git'a i wypuść na swoje repozytorium.

## Struktura DDD (uproszczona) na przykładzie użycia w naszej firmie:

* src
    * Domain
        * Entity
            * Vehicle.php
        * Repository
            * VehicleRepositoryInterface.php (interfejsy repozytorium)
        * Service
            * VehiclesBuilder.php (budowanie listy)
            * VehiclesReader.php (odczytywanie, np. jednego rekordu)
            * VehiclesWriter.php (zapis i aktualizacja)
        * ValueObject
    * Persistence
        * Repository
            * VehicleRepository.php (implementacja repozytoriów)

## Uruchomienie projektu
- `composer install`
- `touch db/assqlite.db` - utworzenie pliku bazy danych
- `yarn install`
- `yarn dev`
- `php -S localhost:8008` - uruchomienie serwera

#### Utworzenie tabeli pojazdów (vehicles) w SQLite:
```SQL
CREATE TABLE vehicles (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  registration_number TEXT(16),
  brand TEXT(60),
  model TEXT(60),
  "type" TEXT,
  created_at INTEGER,
  updated_at INTEGER
);
```

## Endpointy
- `/vehicles` [GET] - index, główny szablon
- `/vehicles/list` [GET] - lista pojazdów
- `/vehicles/save/{id}` [POST] - zapis pojazdu
- `/vehicles/delete/{id}` [POST] - usunięcie pojazdu

## Działanie z kodem - co trzeba zrobić?
Do uzupełnienia jest kontroler `/app/Controller/VehicleController.php` oraz klasy w strukturze DDD w katalogu `/src`.

Całość tabeli i komponentów w Vuetify do uzupełnienia jest w katalogu `/resources`.
Skorzystaj z zainstalowanego już `axios`'a do wywoływania endpointów. Skorzystaj z tabeli `VDataTable` Vuetify i **nie korzystaj** z sortowania i paginacji po stronie backendu.
Zwróć też proszę uwagę, że korzystamy z **Vuetify v2**, a nie v3 - więc dokumentacje przełącz na poprawną wersję

Napisz kod najlepiej jak potrafisz i postaraj się wykorzystać najnowsze praktyki ;)

**Powodzenia!**
