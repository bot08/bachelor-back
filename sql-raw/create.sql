-- Таблиця користувачів
CREATE TABLE Users (
    UserID INTEGER PRIMARY KEY AUTOINCREMENT,
    LastLogin DATETIME,
    Token TEXT,
    RoleID INTEGER NOT NULL,
    Username TEXT UNIQUE NOT NULL,
    Password TEXT NOT NULL,
    Email TEXT UNIQUE NOT NULL,
    FullName TEXT NOT NULL
);

-- Таблиця моделей сонцезахисних окулярів
CREATE TABLE SunglassesModels (
    ModelID INTEGER PRIMARY KEY AUTOINCREMENT,
    ModelManufacturer TEXT NOT NULL,
    ModelName TEXT NOT NULL,
    ModelImage TEXT NOT NULL,
    ModelPolarization BOOLEAN DEFAULT FALSE,
    ModelDescription TEXT,
    ModelPrice DECIMAL(10, 2) NOT NULL
);

-- Таблиця аксесуарів
CREATE TABLE Accessories (
    AccessoryID INTEGER PRIMARY KEY AUTOINCREMENT,
    AccessoryManufacturer TEXT NOT NULL,
    AccessoryImage TEXT NOT NULL,
    AccessoryName TEXT NOT NULL,
    AccessoryDescription TEXT,
    AccessoryPrice DECIMAL(10, 2) NOT NULL
);

-- Таблиця оправ
CREATE TABLE Frames (
    FrameID INTEGER PRIMARY KEY AUTOINCREMENT,
    FrameManufacturer TEXT NOT NULL,
    FrameName TEXT NOT NULL,
    FrameImage TEXT NOT NULL,
    FrameDescription TEXT,
    FramePrice DECIMAL(10, 2) NOT NULL
);

-- Таблиця лінз
CREATE TABLE Lenses (
    LensID INTEGER PRIMARY KEY AUTOINCREMENT,
    LensManufacturer TEXT NOT NULL,
    LensManufacturerCountry TEXT NOT NULL,
    LensType TEXT NOT NULL,
    LensDescription TEXT,
    LensPrice DECIMAL(10, 2) NOT NULL
);

-- Таблиця замовлень
CREATE TABLE Orders (
    OrderID INTEGER PRIMARY KEY AUTOINCREMENT,
    UserID INTEGER NOT NULL,
    DeliveryAddress TEXT NOT NULL,
    FastDelivery BOOLEAN DEFAULT FALSE,
    OrderDate DATE NOT NULL,
    TotalAmount DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

-- Таблиця деталей замовлень
CREATE TABLE OrderDetails (
    DetailID INTEGER PRIMARY KEY AUTOINCREMENT,
    OrderID INTEGER NOT NULL,
    FrameID INTEGER,
    LensID INTEGER,
    DioptersLeft DECIMAL(5, 2),
    DioptersRight DECIMAL(5, 2),
    AstigmatismLeft DECIMAL(5, 2),
    AstigmatismRight DECIMAL(5, 2),
    DP DECIMAL(5, 2),
    LensSettingDescription TEXT,
    LensSettingPrice DECIMAL(10, 2),
    ModelID INTEGER,
    AccessoryID INTEGER,
    Quantity INTEGER NOT NULL,
    UnitPrice DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (OrderID) REFERENCES Orders(OrderID),
    FOREIGN KEY (FrameID) REFERENCES Frames(FrameID),
    FOREIGN KEY (LensID) REFERENCES Lenses(LensID),
    FOREIGN KEY (ModelID) REFERENCES SunglassesModels(ModelID),
    FOREIGN KEY (AccessoryID) REFERENCES Accessories(AccessoryID)
);