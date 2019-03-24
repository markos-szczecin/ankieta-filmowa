# ankieta-filmowa
Zbieranie informacji o gustach filmowych na podstawie filmów pobranych z serwisu FilmWeb

W celu pobranaia informacji o filmach wystarczy odpalić akcję themoviedb w klasie FilmApiController (/film/api/themoviedb). Skrypt pobierze 1000 najpopularniejszych filmów z serwsiu FilmWeb. Liczbę filmów można zwiększyć lub zmniejszyć sterując parameterm w forze. Klasa nie jest napisana optymalnie więc na wolniejszym serwerze pobieranie danych może trochę trwać

Klasa User  zawiera pola passowrd i role, ale są one nieużwane. Użytkonik jest ustalanany na podstawie obecności ciastka "moviemarks". Jeżeli jest ono obecne i zawiera poprawny ID użytkownika to ładowane sa filmy nieocenione jeszcze przez tego użytkonika. Jeżeli ciastko jest nieobecne to użytkownik jest tworzony i dodawane jest ciastko.

Jak działa aplikacja można zobaczyć po adresem https://moviemarks.pl - Zapraszam :)
