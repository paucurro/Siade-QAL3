ALTER TABLE qal3_db.excel_a_generar
    ADD COLUMN IF NOT EXISTS pestanya VARCHAR (15) NULL COMMENT 'Pestaña de la excel que corresponde con el componente' AFTER nombre_documento;

ALTER TABLE qal3_db.qal2
    ADD COLUMN IF NOT EXISTS serie VARCHAR (100) NULL AFTER rango_calibrado,
    ADD COLUMN IF NOT EXISTS frecuencia VARCHAR (100) NULL AFTER serie;
