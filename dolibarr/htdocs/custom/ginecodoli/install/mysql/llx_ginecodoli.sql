-- ============================================================================
-- SCRIPT DE INSTALACIÓN MÓDULO GINECODOLI (FASE 1)
-- Compatible con Dolibarr v19+
-- ============================================================================

-- 1. DICCIONARIOS (Catálogos)
-- -----------------------------------------------------------------------------

-- Tipos de Consulta Ginecológica
CREATE TABLE IF NOT EXISTS llx_c_gynecology_consult_type (
    rowid         INTEGER AUTO_INCREMENT PRIMARY KEY,
    code          VARCHAR(12) NOT NULL UNIQUE,
    label         VARCHAR(50) NOT NULL,
    active        TINYINT DEFAULT 1 NOT NULL,
    module        VARCHAR(32),
    position      INTEGER DEFAULT 0
) ENGINE=innodb;

INSERT INTO llx_c_gynecology_consult_type (rowid, code, label, active, position) VALUES 
(1, 'PRENATAL', 'Control Prenatal', 1, 10),
(2, 'GIN_GENERAL', 'Ginecología General', 1, 20),
(3, 'PLANIFICACION', 'Planificación Familiar', 1, 30),
(4, 'POSTOPERATORIO', 'Control Postoperatorio', 1, 40),
(5, 'URGENCIA', 'Urgencia Ginecológica', 1, 50);

-- Métodos Anticonceptivos
CREATE TABLE IF NOT EXISTS llx_c_gynecology_contraceptive (
    rowid         INTEGER AUTO_INCREMENT PRIMARY KEY,
    code          VARCHAR(12) NOT NULL UNIQUE,
    label         VARCHAR(100) NOT NULL,
    type          VARCHAR(20), -- Hormonal, Barrera, DIU, etc.
    active        TINYINT DEFAULT 1 NOT NULL,
    position      INTEGER DEFAULT 0
) ENGINE=innodb;

INSERT INTO llx_c_gynecology_contraceptive (rowid, code, label, type, active, position) VALUES 
(1, 'ACO_COMB', 'Anticonceptivo Oral Combinado', 'Hormonal', 1, 10),
(2, 'DIU_TCU', 'DIU de Cobre (TCu)', 'DIU', 1, 20),
(3, 'DIU_MIRENA', 'DIU Hormonal (Mirena/Kyleena)', 'DIU', 1, 30),
(4, 'IMPLANTE', 'Implante Subdérmico', 'Hormonal', 1, 40),
(5, 'INJECTABLE', 'Anticonceptivo Inyectable', 'Hormonal', 1, 50),
(6, 'CONDON', 'Preservativo', 'Barrera', 1, 60),
(7, 'LIGADURA', 'Salpingoclasia (Ligadura)', 'Quirúrgico', 1, 70);

-- Tipos de Exámenes / Estudios
CREATE TABLE IF NOT EXISTS llx_c_gynecology_exam_type (
    rowid         INTEGER AUTO_INCREMENT PRIMARY KEY,
    code          VARCHAR(12) NOT NULL UNIQUE,
    label         VARCHAR(100) NOT NULL,
    active        TINYINT DEFAULT 1 NOT NULL,
    position      INTEGER DEFAULT 0
) ENGINE=innodb;

INSERT INTO llx_c_gynecology_exam_type (rowid, code, label, active, position) VALUES 
(1, 'PAP', 'Citología Cervicovaginal (PAP)', 1, 10),
(2, 'COLPOSCOPIA', 'Colposcopía', 1, 20),
(3, 'USG_GINE', 'Ultrasonido Ginecológico', 1, 30),
(4, 'USG_OBST', 'Ultrasonido Obstétrico', 1, 40),
(5, 'MAMOGRAFIA', 'Mastografía / Mamografía', 1, 50),
(6, 'IVS', 'Índice de Vagina Saludable', 1, 60);

-- Procedimientos Quirúrgicos
CREATE TABLE IF NOT EXISTS llx_c_gynecology_surgery (
    rowid         INTEGER AUTO_INCREMENT PRIMARY KEY,
    code          VARCHAR(12) NOT NULL UNIQUE,
    label         VARCHAR(100) NOT NULL,
    active        TINYINT DEFAULT 1 NOT NULL,
    position      INTEGER DEFAULT 0
) ENGINE=innodb;

INSERT INTO llx_c_gynecology_surgery (rowid, code, label, active, position) VALUES 
(1, 'LEGRADO', 'Legrado Uterino', 1, 10),
(2, 'POLIPECTOMIA', 'Polipectomía Endometrial/Cervical', 1, 20),
(3, 'CAUTERIZACION', 'Cauterización Cervical', 1, 30),
(4, 'BIOPSIA', 'Biopsia de Endometrio/Cuello', 1, 40),
(5, 'SALPINGOCLASIA', 'Salpingoclasia Bilateral', 1, 50);


-- 2. TABLAS PRINCIPALES DEL MÓDULO (CommonObject)
-- -----------------------------------------------------------------------------

-- CONSULTAS MÉDICAS (Historia Clínica por visita)
-- Nota: Los datos demográficos vienen de llx_societe. Aquí va lo clínico.
CREATE TABLE IF NOT EXISTS llx_ginecodoli_consulta (
    rowid             INTEGER AUTO_INCREMENT PRIMARY KEY,
    entity            INTEGER DEFAULT 1 NOT NULL,
    date_creation     DATETIME NOT NULL,
    tms               TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    fk_user_creat     INTEGER NOT NULL,
    fk_user_modif     INTEGER,
    
    fk_soc            INTEGER NOT NULL,           -- Link a Paciente (llx_societe)
    fk_actioncomm     INTEGER,                    -- Link opcional a la cita original
    
    fecha_consulta    DATE NOT NULL,
    tipo_consulta     VARCHAR(20),                -- Código del diccionario o texto libre
    
    -- Motivo y Antecedentes inmediatos
    motivo_consulta   TEXT,
    fum               DATE,                       -- Fecha Última Menstruación
    fup               DATE,                       -- Fecha Última Papanicolaou
    
    -- Exploración Física (Ginecológica)
    ta_sistolica      VARCHAR(10),                -- Tensión Arterial
    ta_diastolica     VARCHAR(10),
    peso              DECIMAL(5,2),               -- kg
    talla             DECIMAL(5,2),               -- cm
    imc               DECIMAL(5,2),
    
    mama_inspeccion   TEXT,                       -- Hallazgos mamas
    mama_palpacion    TEXT,
    
    especuloscopia    TEXT,                       -- Hallazgos cuello/vagina
    tacto_vaginal     TEXT,                       -- Hallazgos útero/anexos
    utero_posicion    VARCHAR(50),                -- AVF, AVR, Medio
    utero_tamano      VARCHAR(50),
    anexos            TEXT,
    
    -- Diagnósticos y Plan
    diagnostico       TEXT,                       -- Texto libre o códigos CIE-10 separados por coma
    plan_tratamiento  TEXT,
    medicamentos      TEXT,                       -- JSON o texto detallado
    
    status            INTEGER DEFAULT 1,          -- 1:Borrador, 2:Validada, 0:Cancelada
    import_key        VARCHAR(14)                 -- Para importación externa
) ENGINE=innodb;

-- EMBARAZOS (Seguimiento obstétrico)
CREATE TABLE IF NOT EXISTS llx_ginecodoli_embarazo (
    rowid             INTEGER AUTO_INCREMENT PRIMARY KEY,
    entity            INTEGER DEFAULT 1 NOT NULL,
    date_creation     DATETIME NOT NULL,
    tms               TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    fk_user_creat     INTEGER NOT NULL,
    fk_user_modif     INTEGER,

    fk_soc            INTEGER NOT NULL,           -- Paciente
    fecha_inicio      DATE NOT NULL,              -- FUM confirmada o fecha diagnóstico
    fecha_parto_est   DATE,                       -- Fecha Probable de Parto (FPP)
    
    gestas            INTEGER DEFAULT 0,          -- G
    partos            INTEGER DEFAULT 0,          -- P
    cesareas          INTEGER DEFAULT 0,          -- C
    abortos           INTEGER DEFAULT 0,          -- A
    
    riesgo            VARCHAR(50),                -- Bajo, Alto, Muy Alto
    estado            VARCHAR(50),                -- En curso, Finalizado, Abortado
    
    observaciones     TEXT,
    status            INTEGER DEFAULT 1,          -- 1:Activo, 0:Cerrado/Finalizado
    import_key        VARCHAR(14)
) ENGINE=innodb;

-- EXÁMENES DE LABORATORIO/IMAGEN (Solicitudes y Resultados)
CREATE TABLE IF NOT EXISTS llx_ginecodoli_examen (
    rowid             INTEGER AUTO_INCREMENT PRIMARY KEY,
    entity            INTEGER DEFAULT 1 NOT NULL,
    date_creation     DATETIME NOT NULL,
    tms               TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    fk_user_creat     INTEGER NOT NULL,
    fk_user_modif     INTEGER,

    fk_soc            INTEGER NOT NULL,           -- Paciente
    fk_consulta       INTEGER,                    -- Vinculado a una consulta específica
    
    tipo_examen       VARCHAR(20),                -- Código diccionario
    descripcion       TEXT,                       -- Detalle de la solicitud
    
    fecha_solicitud   DATE,
    fecha_resultado   DATE,
    archivo_adjunto   VARCHAR(255),               -- Ruta al PDF o imagen en Documents
    
    resultado         TEXT,                       -- Texto del resultado o interpretación
    conclusion        VARCHAR(255),               -- Normal / Alterado
    
    status            INTEGER DEFAULT 0,          -- 0:Solicitado, 1:Realizado, 2:Cancelado
    import_key        VARCHAR(14)
) ENGINE=innodb;

-- CIRUGÍAS / PROCEDIMIENTOS MENORES
CREATE TABLE IF NOT EXISTS llx_ginecodoli_cirugia (
    rowid             INTEGER AUTO_INCREMENT PRIMARY KEY,
    entity            INTEGER DEFAULT 1 NOT NULL,
    date_creation     DATETIME NOT NULL,
    tms               TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    fk_user_creat     INTEGER NOT NULL,
    fk_user_modif     INTEGER,

    fk_soc            INTEGER NOT NULL,           -- Paciente
    fk_consulta       INTEGER,
    
    procedimiento     VARCHAR(100),               -- Nombre o código diccionario
    fecha_programada  DATETIME,
    fecha_ejecucion   DATETIME,
    
    anestesia         VARCHAR(50),                -- Local, Regional, General
    complicaciones    TEXT,
    notas_operatorias TEXT,
    
    status            INTEGER DEFAULT 0,          -- 0:Programada, 1:Realizada, 9:Cancelada
    import_key        VARCHAR(14)
) ENGINE=innodb;


-- 3. PERMISOS (Se definirán en PHP, pero aquí dejamos referencia de IDs reservados)
-- Se recomienda usar IDs altos para no colisionar con módulos core.
-- Rango sugerido: 100000 - 100099 para ginecodoli
-- 100001: Ver dashboard
-- 100002: Ver pacientes
-- 100003: Crear/Editar consultas
-- ... etc.
