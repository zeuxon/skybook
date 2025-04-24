CREATE TABLE Repuloter (
    repuloter_id NUMBER(5) PRIMARY KEY,
    nev VARCHAR(255) NOT NULL,
    varos VARCHAR(255) NOT NULL,
    orszag VARCHAR(255) NOT NULL
);

CREATE TABLE Felhasznalo (
    felhasznalo_id NUMBER(5) PRIMARY KEY,
    nev VARCHAR(255) NOT NULL,
    admin NUMBER(1) DEFAULT 0,
    jelszo VARCHAR(255) NOT NULL,
    felhasznalonev VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    telefonszam NUMBER(20),
    iranyitoszam NUMBER(4),
    telepules VARCHAR(100)
);

CREATE SEQUENCE FELHASZNALO_SEQ START WITH 6 INCREMENT BY 1;

CREATE TABLE Ut (
    ut_id NUMBER PRIMARY KEY,
    indulasi_repuloter_id NUMBER(5) NOT NULL,
    erkezesi_repuloter_id NUMBER(5) NOT NULL,
    FOREIGN KEY (indulasi_repuloter_id) REFERENCES Repuloter(repuloter_id) ON DELETE CASCADE,
    FOREIGN KEY (erkezesi_repuloter_id) REFERENCES Repuloter(repuloter_id) ON DELETE CASCADE
);

CREATE TABLE Legitarsasag (
    legitarsasag_id NUMBER(5) PRIMARY KEY,
    nev VARCHAR(255) NOT NULL,
    szekhely VARCHAR(255) NOT NULL,
    orszag VARCHAR(100) NOT NULL
);

CREATE TABLE Repulogep (
    repulogep_id NUMBER(5) PRIMARY KEY,
    legitarsasag_id NUMBER(5) NOT NULL,
    kapacitas NUMBER(3) NOT NULL,
    tipus VARCHAR(255) NOT NULL,
    FOREIGN KEY (legitarsasag_id) REFERENCES Legitarsasag(legitarsasag_id) ON DELETE CASCADE
);

CREATE TABLE Repulojarat (
    jaratid NUMBER(5) PRIMARY KEY,
    repulogep_id NUMBER(5) NOT NULL,
    ut_id NUMBER(5) NOT NULL,
    indulasi_ido DATE NOT NULL,
    erkezesi_ido DATE NOT NULL,
    FOREIGN KEY (repulogep_id) REFERENCES Repulogep(repulogep_id) ON DELETE CASCADE,
    FOREIGN KEY (ut_id) REFERENCES Ut(ut_id) ON DELETE CASCADE
);

CREATE TABLE Pontgyujtes (
    pontgyujtes_id NUMBER(5) PRIMARY KEY,
    felhasznalo_id NUMBER(5) NOT NULL,
    osszeg NUMBER(5) NOT NULL,
    FOREIGN KEY (felhasznalo_id) REFERENCES Felhasznalo(felhasznalo_id) ON DELETE CASCADE
);

CREATE TABLE Foglalas (
    foglalas_id NUMBER(5) PRIMARY KEY,
    felhasznalo_id NUMBER(5) NOT NULL,
    datum DATE NOT NULL,
    statusz VARCHAR(50) NOT NULL,
    FOREIGN KEY (felhasznalo_id) REFERENCES Felhasznalo(felhasznalo_id) ON DELETE CASCADE
);

CREATE TABLE Biztositas (
    biztositas_id NUMBER(5) PRIMARY KEY,
    nev VARCHAR(100) NOT NULL,
    ar NUMBER(10) NOT NULL
);

CREATE TABLE Jegykategoria (
    jegykategoria_id NUMBER(5) PRIMARY KEY,
    nev VARCHAR(100) NOT NULL,
    kedvezmeny_szazalek NUMBER(5) NOT NULL
);

CREATE TABLE Jegy (
    jegy_id NUMBER(5) PRIMARY KEY,
    foglalas_id NUMBER(5) NOT NULL,
    jarat_id NUMBER(5) NOT NULL,
    jegykategoria_id NUMBER(5) NOT NULL,
    ar NUMBER(10) NOT NULL,
    szekhely NUMBER(3) NOT NULL,
    foglalasido DATE NOT NULL,
    FOREIGN KEY (foglalas_id) REFERENCES Foglalas(foglalas_id) ON DELETE CASCADE,
    FOREIGN KEY (jarat_id) REFERENCES Repulojarat(jaratid) ON DELETE CASCADE,
    FOREIGN KEY (jegykategoria_id) REFERENCES Jegykategoria(jegykategoria_id) ON DELETE CASCADE
);

INSERT INTO Repuloter VALUES (1, 'Liszt Ferenc Nemzetközi Repülőtér', 'Budapest', 'Magyarország');
INSERT INTO Repuloter VALUES (2, 'Heathrow', 'London', 'Egyesült Királyság');
INSERT INTO Repuloter VALUES (3, 'Charles de Gaulle', 'Párizs', 'Franciaország');
INSERT INTO Repuloter VALUES (4, 'JFK', 'New York', 'USA');
INSERT INTO Repuloter VALUES (5, 'Schiphol', 'Amszterdam', 'Hollandia');

INSERT INTO Felhasznalo VALUES (1, 'Kiss Péter', 0, 'jelszo123', 'kisspeter', 'kiss.peter@example.com', 36201234567, 1011, 'Budapest');
INSERT INTO Felhasznalo VALUES (2, 'Nagy Anna', 1, 'admin123', 'nagyan', 'nagy.anna@example.com', 36209876543, 1024, 'Budapest');
INSERT INTO Felhasznalo VALUES (3, 'Tóth Gábor', 0, 'titkos123', 'tothg', 'toth.gabor@example.com', 36205551234, 6000, 'Kecskemét');
INSERT INTO Felhasznalo VALUES (4, 'Szabó Erika', 0, 'password', 'szaboerika', 'szabo.erika@example.com', 36207778899, 4026, 'Debrecen');
INSERT INTO Felhasznalo VALUES (5, 'Varga László', 0, 'laszlo123', 'vargalaszlo', 'varga.laszlo@example.com', 36203334455, 6720, 'Szeged');

INSERT INTO Ut VALUES (1, 1, 2);
INSERT INTO Ut VALUES (2, 2, 3);
INSERT INTO Ut VALUES (3, 3, 4);
INSERT INTO Ut VALUES (4, 4, 5);
INSERT INTO Ut VALUES (5, 5, 1);

INSERT INTO Legitarsasag VALUES (1, 'Wizz Air', 'Budapest', 'Magyarország');
INSERT INTO Legitarsasag VALUES (2, 'British Airways', 'London', 'Egyesült Királyság');
INSERT INTO Legitarsasag VALUES (3, 'Air France', 'Párizs', 'Franciaország');
INSERT INTO Legitarsasag VALUES (4, 'Delta Airlines', 'Atlanta', 'USA');
INSERT INTO Legitarsasag VALUES (5, 'KLM', 'Amszterdam', 'Hollandia');

INSERT INTO Repulogep VALUES (1, 1, 180, 'Airbus A320');
INSERT INTO Repulogep VALUES (2, 2, 200, 'Boeing 737');
INSERT INTO Repulogep VALUES (3, 3, 250, 'Airbus A330');
INSERT INTO Repulogep VALUES (4, 4, 300, 'Boeing 777');
INSERT INTO Repulogep VALUES (5, 5, 220, 'Embraer E190');

INSERT INTO Repulojarat VALUES (1, 1, 1, DATE '2025-03-25', DATE '2025-03-25');
INSERT INTO Repulojarat VALUES (2, 2, 2, DATE '2025-03-26', DATE '2025-03-26');
INSERT INTO Repulojarat VALUES (3, 3, 3, DATE '2025-03-27', DATE '2025-03-27');
INSERT INTO Repulojarat VALUES (4, 4, 4, DATE '2025-03-28', DATE '2025-03-28');
INSERT INTO Repulojarat VALUES (5, 5, 5, DATE '2025-03-29', DATE '2025-03-29');

INSERT INTO Pontgyujtes VALUES (1, 1, 500);
INSERT INTO Pontgyujtes VALUES (2, 2, 1000);
INSERT INTO Pontgyujtes VALUES (3, 3, 750);
INSERT INTO Pontgyujtes VALUES (4, 4, 300);
INSERT INTO Pontgyujtes VALUES (5, 5, 1200);

INSERT INTO Foglalas VALUES (1, 1, DATE '2025-03-20', 'Fizetett');
INSERT INTO Foglalas VALUES (2, 2, DATE '2025-03-21', 'Fizetett');
INSERT INTO Foglalas VALUES (3, 3, DATE '2025-03-22', 'Függőben');
INSERT INTO Foglalas VALUES (4, 4, DATE '2025-03-23', 'Fizetett');
INSERT INTO Foglalas VALUES (5, 5, DATE '2025-03-24', 'Törölve');

INSERT INTO Biztositas VALUES (1, 'Alap biztosítás', 5000);
INSERT INTO Biztositas VALUES (2, 'Utazási biztosítás', 10000);
INSERT INTO Biztositas VALUES (3, 'Prémium biztosítás', 15000);
INSERT INTO Biztositas VALUES (4, 'Családi biztosítás', 20000);
INSERT INTO Biztositas VALUES (5, 'Diák biztosítás', 3000);

INSERT INTO Jegykategoria VALUES (1, 'Economy', 0);
INSERT INTO Jegykategoria VALUES (2, 'Premium Economy', 10);
INSERT INTO Jegykategoria VALUES (3, 'Business', 20);
INSERT INTO Jegykategoria VALUES (4, 'First Class', 30);
INSERT INTO Jegykategoria VALUES (5, 'Diák kedvezmény', 15);

INSERT INTO Jegy VALUES (1, 1, 1, 1, 20000, 12, DATE '2025-03-20');
INSERT INTO Jegy VALUES (2, 2, 2, 2, 30000, 15, DATE '2025-03-21');
INSERT INTO Jegy VALUES (3, 3, 3, 3, 50000, 20, DATE '2025-03-22');
INSERT INTO Jegy VALUES (4, 4, 4, 4, 70000, 5, DATE '2025-03-23');
INSERT INTO Jegy VALUES (5, 5, 5, 5, 15000, 30, DATE '2025-03-24');