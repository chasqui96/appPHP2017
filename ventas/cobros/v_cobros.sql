
CREATE OR REPLACE VIEW v_cobros AS 
 SELECT c.id_cobros,
    c.cob_fecha,
    c.cob_efectivo,
    c.cob_estado,
    c.id_apercierre,
    va.funcionario,
    va.caja_descripcion,
    va.id_sucursal,
    va.suc_descripcion,
    to_char(c.cob_fecha, 'dd/mm/yyyy'::text) AS cob_fecha_f,
    ( SELECT v_cuentas_cobrar.cli_razonsocial
           FROM v_cuentas_cobrar
          WHERE v_cuentas_cobrar.id_venta = (( SELECT detalle_cobros.id_venta
                   FROM detalle_cobros
                  WHERE id_cobros = c.id_cobros
                 LIMIT 1)) AND v_cuentas_cobrar.cta_nro = (( SELECT detalle_cobros.cta_nro
                   FROM detalle_cobros
                  WHERE id_cobros = c.id_cobros
                 LIMIT 1))
         LIMIT 1) AS cliente,
    ( SELECT v_cuentas_cobrar.cli_ruc
           FROM v_cuentas_cobrar
          WHERE v_cuentas_cobrar.id_venta = (( SELECT detalle_cobros.id_venta
                   FROM detalle_cobros
                  WHERE id_cobros = c.id_cobros
                 LIMIT 1)) AND v_cuentas_cobrar.cta_nro = (( SELECT detalle_cobros.cta_nro
                   FROM detalle_cobros
                  WHERE id_cobros = c.id_cobros
                 LIMIT 1))
         LIMIT 1) AS ruc,
    ( SELECT v_cuentas_cobrar.id_cliente
           FROM v_cuentas_cobrar
          WHERE v_cuentas_cobrar.id_venta = (( SELECT detalle_cobros.id_venta
                   FROM detalle_cobros
                  WHERE id_cobros = c.id_cobros
                 LIMIT 1)) AND v_cuentas_cobrar.cta_nro = (( SELECT detalle_cobros.cta_nro
                   FROM detalle_cobros
                  WHERE id_cobros = c.id_cobros
                 LIMIT 1))
         LIMIT 1) AS id_cliente,
    c.cob_fecha::date AS fechanormal
   FROM cobros c,
    v_aperturas va
  WHERE va.id_apercierre = c.id_apercierre;

ALTER TABLE v_cobros
  OWNER TO postgres;
