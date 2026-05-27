-- Copyright (C) 2024 Samuel de Dios
-- 
-- This program is free software; you can redistribute it and/or modify
-- it under the terms of the GNU General Public License as published by
-- the Free Software Foundation; either version 3 of the License, or
-- (at your option) any later version.

CREATE TABLE IF NOT EXISTS llx_ginecoplus (
  rowid int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  entity int(11) NOT NULL DEFAULT 1,
  ref varchar(128) NOT NULL UNIQUE,
  label varchar(255),
  description text,
  status int(11) NOT NULL DEFAULT 1,
  date_creation datetime NOT NULL,
  tms timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  fk_user_creat int(11),
  fk_user_modif int(11),
  last_update datetime,
  import_key varchar(14),
  extraparams text,
  KEY idx_entity (entity),
  KEY idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;