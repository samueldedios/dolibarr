# Módulo GinecoDoli para Dolibarr v19+

Módulo de gestión ginecológica nativo para Dolibarr, desarrollado desde cero siguiendo las mejores prácticas del core.

## Características Principales

### Arquitectura Nativa (CORE ONLY)
- **Pacientes = Societe**: Uso de `llx_societe` + Extrafields para datos ginecológicos
- **Citas = ActionComm**: Extensión de la agenda nativa de Dolibarr
- **Consultas/Embarazos/Exámenes/Cirugías**: Tablas y clases propias (CommonObject)
- **Diccionarios**: Tablas `llx_c_gynecology_*` para catálogos

### Funcionalidades Implementadas

#### 1. Dashboard
- Widgets KPI con ModeleBoxes style
- Total pacientes, consultas del mes, embarazos en curso, citas próximas
- Accesos rápidos a todas las entidades
- Listado de últimas consultas
- Exámenes pendientes de resultado

#### 2. Pacientes (Societe + Extrafields)
- Campos ginecológicos: Menarquia, FUM, G/P/C/A, Grupo Sanguíneo, Factor RH
- Categoría "Pacientes Ginecología" para filtrado
- Historial clínico vinculado

#### 3. Citas (ActionComm nativo)
- Tipos de cita personalizados
- Vinculación con pacientes
- Recordatorios y alertas

#### 4. Consultas (llx_ginecodoli_consulta)
- Motivo de consulta, FUM, FUP
- Signos vitales (TA, Peso, Talla, IMC automático)
- Exploración física: Mamas, Especuloscopia, Tacto vaginal
- Diagnóstico y plan de tratamiento
- Estados: Borrador, Validada, Cancelada

#### 5. Embarazos (llx_ginecodoli_embarazo)
- Seguimiento obstétrico completo
- Cálculo automático de edad gestacional y FPP
- Clasificación G/P/C/A
- Estados: En curso, Finalizado, Abortado

#### 6. Anticoncepción
- Diccionario de métodos anticonceptivos
- Tipos: Hormonal, DIU, Barrera, Quirúrgico

#### 7. Exámenes (llx_ginecodoli_examen)
- Solicitudes de laboratorio e imagen
- Tipos: PAP, Colposcopía, Ultrasonido, Mamografía
- Estados: Solicitado, Realizado, Cancelado
- Adjunto de archivos

#### 8. Cirugías (llx_ginecodoli_cirugia)
- Procedimientos quirúrgicos menores
- Programación y ejecución
- Notas operatorias

#### 9. Reportes
- Estadísticas básicas
- Listados filtrables

## Estructura de Directorios

```
ginecodoli/
├── admin/                  # Páginas de administración
│   └── ginecodoli_setup.php
├── class/                  # Clases PHP (CommonObject)
│   └── consulta.class.php
├── core/
│   └── modules/
│       └── modGinecoDoli.class.php
├── css/                    # Hojas de estilo personalizadas
├── img/                    # Iconos del módulo
├── install/
│   └── mysql/
│       └── llx_ginecodoli.sql
├── langs/
│   └── es_ES.langs
├── lib/                    # Librerías y funciones comunes
│   └── ginecodoli.lib.php
├── index.php               # Dashboard principal
└── README.md
```

## Instalación

### Requisitos Previos
- Dolibarr v19 o superior
- PHP 7.4+
- MySQL/MariaDB

### Pasos de Instalación

1. **Copiar archivos**
   ```bash
   cp -r ginecodoli /ruta/a/dolibarr/htdocs/custom/
   ```

2. **Configurar permisos**
   ```bash
   chown -R www-data:www-data /ruta/a/dolibarr/htdocs/custom/ginecodoli
   chmod -R 755 /ruta/a/dolibarr/htdocs/custom/ginecodoli
   ```

3. **Activar módulo**
   - Ir a: Inicio → Configuración → Módulos/Apps
   - Buscar "GinecoDoli" en la lista
   - Hacer clic en el interruptor para activar
   - El script SQL se ejecutará automáticamente

4. **Configurar extrafields**
   - Ir a: GinecoDoli → Configuración
   - Clic en "Añadir Campos Ginecológicos"
   - Clic en "Crear Categoría Pacientes"

5. **Asignar permisos**
   - Ir a: Inicio → Usuarios y Grupos
   - Seleccionar usuario/grupo
   - Asignar permisos de GinecoDoli según roles

## Permisos ACL

| ID | Permiso | Descripción |
|----|---------|-------------|
| 100501 | ginecodoli.read | Leer módulo |
| 100502 | ginecodoli.dashboard | Ver dashboard |
| 100510 | ginecodoli.patient.lire | Ver pacientes |
| 100511 | ginecodoli.patient.write | Crear/Editar pacientes |
| 100520 | ginecodoli.consulta.lire | Ver consultas |
| 100521 | ginecodoli.consulta.write | Crear/Editar consultas |
| 100522 | ginecodoli.consulta.delete | Eliminar consultas |
| 100530 | ginecodoli.embarazo.lire | Ver embarazos |
| 100531 | ginecodoli.embarazo.write | Crear/Editar embarazos |
| 100540 | ginecodoli.examen.lire | Ver exámenes |
| 100541 | ginecodoli.examen.write | Crear/Editar exámenes |
| 100550 | ginecodoli.cirugia.lire | Ver cirugías |
| 100551 | ginecodoli.cirugia.write | Crear/Editar cirugías |

## Diccionarios Incluidos

### Tipos de Consulta (llx_c_gynecology_consult_type)
- PRENATAL: Control Prenatal
- GIN_GENERAL: Ginecología General
- PLANIFICACION: Planificación Familiar
- POSTOPERATORIO: Control Postoperatorio
- URGENCIA: Urgencia Ginecológica

### Métodos Anticonceptivos (llx_c_gynecology_contraceptive)
- ACO_COMB: Anticonceptivo Oral Combinado
- DIU_TCU: DIU de Cobre
- DIU_MIRENA: DIU Hormonal
- IMPLANTE: Implante Subdérmico
- INJECTABLE: Inyectable
- CONDON: Preservativo
- LIGADURA: Salpingoclasia

### Tipos de Exámenes (llx_c_gynecology_exam_type)
- PAP: Citología Cervicovaginal
- COLPOSCOPIA: Colposcopía
- USG_GINE: Ultrasonido Ginecológico
- USG_OBST: Ultrasonido Obstétrico
- MAMOGRAFIA: Mastografía
- IVS: Índice de Vagina Saludable

### Procedimientos Quirúrgicos (llx_c_gynecology_surgery)
- LEGRADO: Legrado Uterino
- POLIPECTOMIA: Polipectomía
- CAUTERIZACION: Cauterización Cervical
- BIOPSIA: Biopsia
- SALPINGOCLASIA: Salpingoclasia Bilateral

## Seguridad

- Todas las consultas usan `$this->db->prepare()` con placeholders
- Escape de HTML con `dol_escape_htmltag()`
- Verificación de permisos con `$user->rights`
- Protección CSRF con tokens
- Multi-empresa (entity) habilitado

## Desarrollo Futuro (Roadmap)

- [ ] Recetas médicas con impresión PDF
- [ ] Consentimientos informados
- [ ] Telemedicina integrada
- [ ] Recordatorios SMS/Email
- [ ] Estadísticas avanzadas con DolGraph
- [ ] Exportación a formatos estándar (HL7, FHIR)

## Licencia

GNU/GPL v3 o posterior

## Soporte

Para issues o contribuciones, contactar al equipo de desarrollo.
