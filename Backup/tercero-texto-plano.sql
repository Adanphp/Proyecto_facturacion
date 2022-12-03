--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.2
-- Dumped by pg_dump version 9.5.2

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

--
-- Name: estados; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE estados AS ENUM (
    'ACTIVO',
    'INACTIVO',
    'PENDIENTE',
    'CONFIRMADO',
    'ANULADO',
    'ORDENADO',
    'FACTURADO',
    'RECEPCIONADO'
);


ALTER TYPE estados OWNER TO postgres;

--
-- Name: sp_paises(integer, character varying, character varying, character varying, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION sp_paises(cid_pais integer, cpais_descrip character varying, cgentilicio character varying, cusuario character varying, operacion integer) RETURNS void
    LANGUAGE plpgsql
    AS $$
BEGIN
	IF(operacion = 1)THEN --INSERTAR
		PERFORM * FROM paises WHERE pais_descrip = UPPER(TRIM(cpais_descrip));
		IF FOUND THEN
			RAISE EXCEPTION '%','YA EXISTE';
		END IF;
		INSERT INTO paises (id_pais, pais_descrip, gentilicio, estado, auditoria)
		VALUES ((SELECT COALESCE(MAX(id_pais),0) + 1 FROM paises), UPPER(TRIM(cpais_descrip)), UPPER(TRIM(cgentilicio)), 'ACTIVO', ('INSERCION/'||cusuario||'/'||NOW()));
		RAISE NOTICE '%','DATOS GUARDADOS CON ÉXITO';
	END IF;
	IF(operacion = 2)THEN --MODIFICAR
		PERFORM * FROM paises WHERE pais_descrip = UPPER(TRIM(cpais_descrip)) AND id_pais != cid_pais;
		IF FOUND THEN
			RAISE EXCEPTION '%','YA EXISTE';
		END IF;
		UPDATE paises SET pais_descrip = cpais_descrip, gentilicio = cgentilicio, auditoria = COALESCE(auditoria,'')||chr(13)||'MODIFICACION/'||cusuario||'/'||NOW() WHERE id_pais = cid_pais;
		RAISE NOTICE '%','DATOS GUARDADOS CON ÉXITO';
	END IF;
	IF(operacion = 3)THEN --ACTIVAR
		UPDATE paises SET estado = 'ACTIVO', auditoria = COALESCE(auditoria,'')||chr(13)||'ACTIVACION/'||cusuario||'/'||NOW() WHERE id_pais = cid_pais;
		RAISE NOTICE '%','DATOS GUARDADOS CON ÉXITO';
	END IF;
	IF(operacion = 4)THEN --INACTIVAR
		UPDATE paises SET estado = 'INACTIVO', auditoria = COALESCE(auditoria,'')||chr(13)||'INACTIVACION/'||cusuario||'/'||NOW() WHERE id_pais = cid_pais;
		RAISE NOTICE '%','DATOS GUARDADOS CON ÉXITO';
	END IF;
END;
$$;


ALTER FUNCTION public.sp_paises(cid_pais integer, cpais_descrip character varying, cgentilicio character varying, cusuario character varying, operacion integer) OWNER TO postgres;

--
-- Name: sp_personas(integer, character varying, character varying, character varying, character varying, date, character varying, character varying, character varying, integer, integer, integer, character varying, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION sp_personas(cid_per integer, cper_ci character varying, cper_ruc character varying, cper_nombre character varying, cper_apellido character varying, cper_fenaci date, cper_celular character varying, cper_email character varying, cper_direccion character varying, cid_ciu integer, cid_gen integer, cid_ec integer, usuario character varying, operacion integer) RETURNS void
    LANGUAGE plpgsql
    AS $$
begin
	if operacion = 1 then
		perform * from personas where per_ci = upper(trim(cper_ci)) and id_ciu in (select id_ciu from ciudades where id_pais = (select id_pais from ciudades where id_ciu = cid_ciu));
		if found then
			raise notice 'ESTA PERSONA YA EXISTE';
		end if;
		insert into personas (id_per, per_ci, per_ruc, per_nombre, per_apellido, per_fenaci, per_celular, per_email, per_direccion, id_ciu, id_gen, id_ec, estado, auditoria)
		values ((select coalesce(max(id_per),0) +1 from personas), trim(cper_ci), trim(cper_ruc), upper(trim(cper_nombre)), upper(trim(cper_apellido)), cper_fenaci, trim(cper_celular), trim(cper_email), trim(cper_direccion), cid_ciu, cid_gen, cid_ec, 'ACTIVO', 'INSERCION/'||usuario||'/'||now());
		raise notice 'GUARDADO CON EXITO';
	end if;
	if operacion = 2 then
		perform * from personas where per_ci = upper(trim(cper_ci)) and id_ciu in (select id_ciu from ciudades where id_pais = (select id_pais from ciudades where id_ciu = cid_ciu)) and id_per != cid_per;
		if found then
			raise notice 'ESTA PERSONA YA EXISTE';
		end if;
		update personas set per_ci = upper(trim(cper_ci)), per_ruc = upper(trim(cper_ruc)), per_nombre = upper(trim(cper_nombre)), per_apellido = upper(trim(cper_apellido)), per_fenaci = cper_fenaci, per_celular = trim(cper_celular), per_email = trim(cper_email), per_direccion = trim(cper_direccion), id_ciu = cid_ciu, id_gen = cid_gen, id_ec = cid_ec, auditoria = coalesce(auditoria,'')||chr(13)||'MODIFICACION/'||usuario||'/'||now() where id_per = cid_per;
		raise notice 'DATOS MODIFICADOS CON EXITO';
	end if;
	if operacion = 3 then
		update personas set estado = 'ACTIVO', auditoria = coalesce(auditoria,'')||chr(13)||'ACTIVACION/'||usuario||'/'||now() where id_per = cid_per;
	end if;
	if operacion = 4 then
		update personas set estado = 'INACTIVO', auditoria = coalesce(auditoria,'')||chr(13)||'INACTIVACION/'||usuario||'/'||now() where id_per = cid_per;
	end if;
end;
$$;


ALTER FUNCTION public.sp_personas(cid_per integer, cper_ci character varying, cper_ruc character varying, cper_nombre character varying, cper_apellido character varying, cper_fenaci date, cper_celular character varying, cper_email character varying, cper_direccion character varying, cid_ciu integer, cid_gen integer, cid_ec integer, usuario character varying, operacion integer) OWNER TO postgres;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: acciones; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE acciones (
    id_ac integer NOT NULL,
    ac_descrip character varying NOT NULL,
    estado character varying NOT NULL,
    auditoria character varying NOT NULL
);


ALTER TABLE acciones OWNER TO postgres;

--
-- Name: cargos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE cargos (
    id_car integer NOT NULL,
    car_descrip character varying NOT NULL,
    estado character varying NOT NULL,
    auditoria character varying NOT NULL
);


ALTER TABLE cargos OWNER TO postgres;

--
-- Name: ciudades; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ciudades (
    id_ciu integer NOT NULL,
    ciu_descrip character varying,
    id_pais integer,
    estado character varying,
    auditoria text
);


ALTER TABLE ciudades OWNER TO postgres;

--
-- Name: clasificaciones; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE clasificaciones (
    id_cla integer NOT NULL,
    cla_descrip character varying,
    estado estados,
    auditoria text
);


ALTER TABLE clasificaciones OWNER TO postgres;

--
-- Name: colores_cabecera; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE colores_cabecera (
    id_cc integer NOT NULL,
    cc_descrip character varying
);


ALTER TABLE colores_cabecera OWNER TO postgres;

--
-- Name: colores_logo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE colores_logo (
    id_cl integer NOT NULL,
    cl_descrip character varying
);


ALTER TABLE colores_logo OWNER TO postgres;

--
-- Name: colores_menu; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE colores_menu (
    id_cm integer NOT NULL,
    cm_descrip character varying
);


ALTER TABLE colores_menu OWNER TO postgres;

--
-- Name: estados_civiles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE estados_civiles (
    id_ec integer NOT NULL,
    ec_descrip character varying,
    estado character varying,
    auditoria text
);


ALTER TABLE estados_civiles OWNER TO postgres;

--
-- Name: funcionarios; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE funcionarios (
    id_fun integer NOT NULL,
    id_per integer NOT NULL,
    id_car integer,
    estado character varying NOT NULL,
    auditoria character varying NOT NULL
);


ALTER TABLE funcionarios OWNER TO postgres;

--
-- Name: generos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE generos (
    id_gen integer NOT NULL,
    gen_descrip character varying,
    estado character varying,
    auditoria text
);


ALTER TABLE generos OWNER TO postgres;

--
-- Name: grupos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE grupos (
    id_gru integer NOT NULL,
    gru_descrip character varying NOT NULL,
    estado character varying NOT NULL,
    auditoria character varying NOT NULL
);


ALTER TABLE grupos OWNER TO postgres;

--
-- Name: modulos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE modulos (
    id_mod integer NOT NULL,
    mod_descrip character varying NOT NULL,
    estado character varying NOT NULL,
    auditoria character varying NOT NULL,
    icono character varying
);


ALTER TABLE modulos OWNER TO postgres;

--
-- Name: paginas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE paginas (
    id_pag integer NOT NULL,
    estado character varying NOT NULL,
    auditoria character varying NOT NULL,
    pag_descrip character varying NOT NULL,
    pag_ubicacion character varying NOT NULL,
    id_mod integer NOT NULL,
    icono character varying
);


ALTER TABLE paginas OWNER TO postgres;

--
-- Name: paises; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE paises (
    id_pais integer NOT NULL,
    pais_descrip character varying,
    gentilicio character varying,
    estado character varying,
    auditoria text,
    pais_abreviatura character varying
);


ALTER TABLE paises OWNER TO postgres;

--
-- Name: permisos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE permisos (
    id_pag integer NOT NULL,
    id_ac integer NOT NULL,
    id_gru integer NOT NULL,
    estado character varying NOT NULL,
    auditoria character varying NOT NULL
);


ALTER TABLE permisos OWNER TO postgres;

--
-- Name: personas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE personas (
    id_per integer NOT NULL,
    per_ci character varying,
    per_ruc character varying,
    per_nombre character varying,
    per_apellido character varying,
    per_fenaci date,
    per_celular character varying,
    per_email character varying,
    per_direccion character varying,
    id_ciu integer,
    id_gen integer,
    id_ec integer,
    estado character varying,
    auditoria character varying
);


ALTER TABLE personas OWNER TO postgres;

--
-- Name: sucursales; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE sucursales (
    id_suc integer NOT NULL,
    suc_ruc character varying NOT NULL,
    suc_nombre character varying NOT NULL,
    suc_email character varying NOT NULL,
    suc_celular character varying NOT NULL,
    suc_direccion character varying NOT NULL,
    suc_ubicacion character varying NOT NULL,
    estado character varying NOT NULL,
    auditoria character varying NOT NULL,
    suc_imagen character varying,
    id_ciu integer,
    suc_abreviacion character varying
);


ALTER TABLE sucursales OWNER TO postgres;

--
-- Name: usuario_sucursal; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE usuario_sucursal (
    id_usu integer NOT NULL,
    id_suc integer NOT NULL,
    estado character varying NOT NULL,
    auditoria character varying NOT NULL
);


ALTER TABLE usuario_sucursal OWNER TO postgres;

--
-- Name: usuarios; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE usuarios (
    id_usu integer NOT NULL,
    usu_nombre character varying NOT NULL,
    usu_contrasena character varying NOT NULL,
    ultima_sesion character varying NOT NULL,
    id_gru integer NOT NULL,
    id_fun integer NOT NULL,
    auditoria character varying NOT NULL,
    estado character varying NOT NULL,
    id_suc integer,
    usu_imagen character varying,
    id_cc integer,
    id_cl integer,
    id_cm integer
);


ALTER TABLE usuarios OWNER TO postgres;

--
-- Name: v_permisos; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW v_permisos AS
 SELECT pe.id_pag,
    pa.id_mod,
    pe.id_ac,
    pe.id_gru,
    pe.estado AS pe_estado,
    pa.estado AS pa_estado,
    m.estado AS m_estado,
    a.estado AS a_estado,
    g.estado AS g_estado,
    pa.pag_descrip,
    pa.pag_ubicacion,
    m.mod_descrip,
    a.ac_descrip,
    g.gru_descrip,
    pa.icono AS pa_icono,
    m.icono AS m_icono
   FROM ((((permisos pe
     JOIN paginas pa ON ((pa.id_pag = pe.id_pag)))
     JOIN modulos m ON ((m.id_mod = pa.id_mod)))
     JOIN acciones a ON ((a.id_ac = pe.id_ac)))
     JOIN grupos g ON ((g.id_gru = pe.id_gru)));


ALTER TABLE v_permisos OWNER TO postgres;

--
-- Name: v_personas; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW v_personas AS
 SELECT p.id_per,
    p.per_ci,
    p.per_ruc,
    p.per_nombre,
    p.per_apellido,
    p.per_fenaci,
    p.per_celular,
    p.per_email,
    p.per_direccion,
    p.id_ciu,
    p.id_gen,
    p.id_ec,
    p.estado,
    p.auditoria,
    c.ciu_descrip,
    c.id_pais,
    pa.gentilicio,
    pa.pais_descrip,
    pa.pais_abreviatura,
    g.gen_descrip,
    ec.ec_descrip
   FROM ((((personas p
     JOIN ciudades c ON ((c.id_ciu = p.id_ciu)))
     JOIN generos g ON ((g.id_gen = p.id_gen)))
     JOIN estados_civiles ec ON ((ec.id_ec = p.id_ec)))
     JOIN paises pa ON ((pa.id_pais = c.id_pais)));


ALTER TABLE v_personas OWNER TO postgres;

--
-- Name: v_usuarios; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW v_usuarios AS
 SELECT u.id_usu,
    u.usu_nombre,
    u.usu_contrasena,
    u.ultima_sesion,
    u.usu_imagen,
    u.id_gru,
    u.id_fun,
    u.estado,
    u.id_suc,
    u.id_cc,
    u.id_cl,
    u.id_cm,
    u.auditoria,
    cc.cc_descrip,
    cl.cl_descrip,
    cm.cm_descrip,
    g.gru_descrip,
    f.id_per,
    f.id_car,
    p.per_ci,
    p.per_nombre,
    p.per_apellido,
    c.car_descrip,
    s.suc_nombre,
    s.suc_direccion,
    s.suc_email,
    s.suc_ruc,
    s.suc_celular,
    s.suc_ubicacion,
    s.suc_imagen,
    s.suc_abreviacion
   FROM ((((((((usuarios u
     JOIN colores_cabecera cc ON ((cc.id_cc = u.id_cc)))
     JOIN colores_logo cl ON ((cl.id_cl = u.id_cl)))
     JOIN colores_menu cm ON ((cm.id_cm = u.id_cm)))
     JOIN grupos g ON ((g.id_gru = u.id_gru)))
     JOIN funcionarios f ON ((f.id_fun = u.id_fun)))
     JOIN sucursales s ON ((s.id_suc = u.id_suc)))
     JOIN cargos c ON ((c.id_car = f.id_car)))
     JOIN personas p ON ((p.id_per = f.id_per)));


ALTER TABLE v_usuarios OWNER TO postgres;

--
-- Data for Name: acciones; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO acciones (id_ac, ac_descrip, estado, auditoria) VALUES (6, 'INACTIVAR', 'ACTIVO', 'ctorres');
INSERT INTO acciones (id_ac, ac_descrip, estado, auditoria) VALUES (5, 'ACTIVAR', 'ACTIVO', 'ctorres');
INSERT INTO acciones (id_ac, ac_descrip, estado, auditoria) VALUES (4, 'MODIFICAR', 'ACTIVO', 'ctorres');
INSERT INTO acciones (id_ac, ac_descrip, estado, auditoria) VALUES (3, 'BORRAR', 'ACTIVO', 'ctorres');
INSERT INTO acciones (id_ac, ac_descrip, estado, auditoria) VALUES (2, 'AGREGAR', 'ACTIVO', 'ctorres');
INSERT INTO acciones (id_ac, ac_descrip, estado, auditoria) VALUES (1, 'VISUALIZAR', 'ACTIVO', 'ctorres');
INSERT INTO acciones (id_ac, ac_descrip, estado, auditoria) VALUES (7, 'CONFIRMAR', 'ACTIVO', 'INSERCION/ctorres/::1/2019-10-11 20:22:34.431887-03INACTIVACION/ctorres/::1/2019-10-11 20:23:15.798016-03ACTIVACION/ctorres/::1/2019-10-11 20:23:19.122103-03MODIFICACION/ctorres/::1/2019-10-11 20:23:43.871893-03');
INSERT INTO acciones (id_ac, ac_descrip, estado, auditoria) VALUES (8, 'ANULAR', 'ACTIVO', 'INSERCION/ctorres/::1/2019-10-11 20:23:11.379773-03');


--
-- Data for Name: cargos; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO cargos (id_car, car_descrip, estado, auditoria) VALUES (7, 'GERENTE DE VENTA', 'ACTIVO', 'INSERCION/ctorres/::1/2019-09-17 09:19:12.53975-04MODIFICACION/ctorres/::1/2019-09-17 09:19:31.897823-04');
INSERT INTO cargos (id_car, car_descrip, estado, auditoria) VALUES (8, 'CARGO1', 'ACTIVO', 'INSERCION/ctorres/::1/2019-10-11 18:45:54.674659-03');
INSERT INTO cargos (id_car, car_descrip, estado, auditoria) VALUES (5, 'GERENTE DE COMPRA', 'INACTIVO', 'INSERCION/ctorres/::1/2019-09-11 09:54:25.914458-04INACTIVACION/ctorres/::1/2019-09-11 11:24:33.105705-04ACTIVACION/ctorres/::1/2019-09-11 11:26:19.154602-04INACTIVACION/ctorres/::1/2019-09-11 11:26:29.091166-04ACTIVACION/ctorres/::1/2019-09-11 14:41:01.19612-04MODIFICACION/ctorres/::1/2019-09-11 14:41:24.467088-04INACTIVACION/ctorres/::1/2019-10-11 18:46:08.227284-03');
INSERT INTO cargos (id_car, car_descrip, estado, auditoria) VALUES (6, 'GERENTE DE PRODUCCION', 'INACTIVO', 'INSERCION/ctorres/::1/2019-09-11 09:54:31.129408-04INACTIVACION/ctorres/::1/2019-09-11 11:24:40.707469-04ACTIVACION/ctorres/::1/2019-09-11 11:26:22.152721-04INACTIVACION/ctorres/::1/2019-09-11 11:26:26.124241-04ACTIVACION/ctorres/::1/2019-09-11 14:41:03.740476-04MODIFICACION/ctorres/::1/2019-09-11 14:41:41.435684-04INACTIVACION/ctorres/::1/2019-10-11 20:10:11.541513-03');
INSERT INTO cargos (id_car, car_descrip, estado, auditoria) VALUES (10, 'FADSDSAFAF', 'ACTIVO', 'INSERCION/ctorres/::1/2019-10-11 20:10:48.495329-03');
INSERT INTO cargos (id_car, car_descrip, estado, auditoria) VALUES (9, 'ASDFFDSA', 'INACTIVO', 'INSERCION/ctorres/::1/2019-10-11 20:10:44.60875-03INACTIVACION/ctorres/::1/2020-02-18 11:57:14.198666-03INACTIVACION/ctorres/::1/2020-02-18 11:57:19.164828-03');
INSERT INTO cargos (id_car, car_descrip, estado, auditoria) VALUES (4, 'RECEPCIONISTA', 'ACTIVO', 'ACTIVACION/ctorres/::1/2019-09-11 11:18:35.297594-04ACTIVACION/ctorres/::1/2019-09-11 11:20:31.046472-04INACTIVACION/ctorres/::1/2019-09-11 11:24:29.275088-04ACTIVACION/ctorres/::1/2019-09-11 11:26:15.610292-04INACTIVACION/ctorres/::1/2019-09-17 09:20:36.291775-04ACTIVACION/ctorres/::1/2019-09-17 09:20:38.523266-04INACTIVACION/ctorres/::1/2019-10-11 20:10:13.329009-03ACTIVACION/ctorres/::1/2020-01-25 10:15:30.565359-03INACTIVACION/ctorres/::1/2020-01-25 10:15:39.026156-03ACTIVACION/ctorres/::1/2020-06-25 17:53:07.189064-04');
INSERT INTO cargos (id_car, car_descrip, estado, auditoria) VALUES (3, 'DIRECTOR/RA RRHH', 'ACTIVO', 'ACTIVACION/ctorres/::1/2019-09-11 11:20:28.675619-04INACTIVACION/ctorres/::1/2019-09-11 11:24:27.386537-04ACTIVACION/ctorres/::1/2019-09-11 11:26:14.1309-04MODIFICACION/ctorres/::1/2019-10-11 20:15:43.807418-03INACTIVACION/ctorres/::1/2020-01-25 10:15:37.192698-03ACTIVACION/ctorres/::1/2020-06-25 17:53:05.471438-04MODIFICACION/ctorres/::1/2020-06-25 17:53:33.461053-04MODIFICACION/ctorres/::1/2020-06-25 17:53:44.288554-04');
INSERT INTO cargos (id_car, car_descrip, estado, auditoria) VALUES (1, 'DESARROLLADOR', 'INACTIVO', 'MODIFICACION/ctorres/::1/2019-09-11 10:07:48.64173-04MODIFICACION/ctorres/::1/2019-09-11 10:26:19.868154-04MODIFICACION/ctorres/::1/2019-09-11 10:26:23.264283-04MODIFICACION/ctorres/::1/2019-09-11 10:26:38.101566-04MODIFICACION/ctorres/::1/2019-09-11 10:26:44.951452-04MODIFICACION/ctorres/::1/2019-09-11 10:26:55.354639-04MODIFICACION/ctorres/::1/2019-09-11 10:26:59.193977-04INACTIVACION/ctorres/::1/2019-09-11 11:23:00.628746-04ACTIVACION/ctorres/::1/2019-09-11 11:23:05.919032-04INACTIVACION/ctorres/::1/2019-09-11 11:23:09.642557-04ACTIVACION/ctorres/::1/2019-09-11 11:23:50.792426-04INACTIVACION/ctorres/::1/2019-09-11 11:24:16.547656-04ACTIVACION/ctorres/::1/2019-09-11 11:24:18.443038-04INACTIVACION/ctorres/::1/2019-09-11 11:24:25.826378-04ACTIVACION/ctorres/::1/2019-09-11 11:24:42.618008-04MODIFICACION/ctorres/::1/2019-09-11 11:24:45.785845-04MODIFICACION/ctorres/::1/2019-09-11 11:25:51.845034-04MODIFICACION/ctorres/::1/2019-09-11 11:25:58.896796-04MODIFICACION/ctorres/::1/2019-09-11 11:26:02.594949-04INACTIVACION/ctorres/::1/2019-09-11 11:26:05.668374-04ACTIVACION/ctorres/::1/2019-09-11 11:26:10.538316-04MODIFICACION/ctorres/::1/2019-09-11 14:25:38.929213-04MODIFICACION/ctorres/::1/2019-09-11 14:25:56.92408-04MODIFICACION/ctorres/::1/2019-09-11 14:26:02.7527-04MODIFICACION/ctorres/::1/2019-09-11 14:26:06.745434-04INACTIVACION/ctorres/::1/2019-09-11 14:26:09.728556-04ACTIVACION/ctorres/::1/2019-09-11 14:26:13.421206-04INACTIVACION/ctorres/::1/2019-09-16 09:49:10.226281-04ACTIVACION/ctorres/::1/2019-09-16 09:49:12.050483-04INACTIVACION/ctorres/::1/2019-09-16 09:57:00.194502-04ACTIVACION/ctorres/::1/2019-09-16 09:57:02.49814-04MODIFICACION/ctorres/::1/2019-09-16 09:57:05.322078-04MODIFICACION/ctorres/::1/2019-09-16 09:57:08.303286-04INACTIVACION/ctorres/::1/2019-09-16 11:54:31.041879-04ACTIVACION/ctorres/::1/2019-09-16 11:54:33.248705-04MODIFICACION/ctorres/::1/2019-09-16 11:54:35.775889-04MODIFICACION/ctorres/::1/2019-09-16 11:54:39.200966-04MODIFICACION/ctorres/::1/2019-09-17 09:20:14.222381-04MODIFICACION/ctorres/::1/2019-09-17 09:20:18.429462-04MODIFICACION/ctorres/::1/2019-09-18 10:45:11.078641-04MODIFICACION/ctorres/::1/2019-09-18 10:45:16.340724-04INACTIVACION/ctorres/::1/2019-10-08 10:44:33.250588-03ACTIVACION/ctorres/::1/2019-10-08 10:44:36.000971-03MODIFICACION/ctorres/::1/2019-10-08 10:44:43.380627-03INACTIVACION/ctorres/::1/2019-10-09 20:34:34.756253-03ACTIVACION/ctorres/::1/2019-10-09 20:34:42.385223-03INACTIVACION/ctorres/::1/2019-10-09 20:44:12.166306-03ACTIVACION/ctorres/::1/2019-10-11 18:46:10.899374-03MODIFICACION/ctorres/::1/2019-10-11 18:46:21.57155-03INACTIVACION/ctorres/::1/2019-10-11 19:58:05.477841-03ACTIVACION/ctorres/::1/2019-10-11 19:58:08.607605-03INACTIVACION/ctorres/::1/2019-10-11 19:58:10.130468-03ACTIVACION/ctorres/::1/2019-10-11 19:58:11.639972-03INACTIVACION/ctorres/::1/2019-10-11 19:58:13.586542-03ACTIVACION/ctorres/::1/2019-10-11 19:58:21.965959-03INACTIVACION/ctorres/::1/2019-10-11 19:59:00.755784-03ACTIVACION/ctorres/::1/2019-10-11 19:59:07.217942-03INACTIVACION/ctorres/::1/2019-10-11 20:06:45.18121-03ACTIVACION/ctorres/::1/2020-06-25 17:53:01.207628-04INACTIVACION/ctorres/::1/2020-08-05 19:30:19.572911-04');
INSERT INTO cargos (id_car, car_descrip, estado, auditoria) VALUES (2, 'ADMINISTRADOR DE BASE DE DATOS', 'INACTIVO', 'INACTIVACION/ctorres/::1/2019-09-11 11:23:46.947165-04ACTIVACION/ctorres/::1/2019-09-11 11:23:49.154468-04INACTIVACION/ctorres/::1/2019-09-11 11:23:54.57235-04ACTIVACION/ctorres/::1/2019-09-11 11:24:21.188668-04INACTIVACION/ctorres/::1/2019-09-11 11:24:23.977636-04ACTIVACION/ctorres/::1/2019-09-11 11:24:48.426559-04MODIFICACION/ctorres/::1/2019-09-11 11:24:56.571932-04MODIFICACION/ctorres/::1/2019-09-11 11:25:00.163037-04INACTIVACION/ctorres/::1/2019-09-11 11:26:07.979261-04ACTIVACION/ctorres/::1/2019-09-11 11:26:12.61153-04INACTIVACION/ctorres/::1/2020-01-25 10:15:35.411853-03ACTIVACION/ctorres/::1/2020-06-25 17:53:03.579894-04INACTIVACION/ctorres/::1/2020-08-05 19:30:28.80044-04');
INSERT INTO cargos (id_car, car_descrip, estado, auditoria) VALUES (11, 'ADSF', 'ACTIVO', 'INSERCION/ctorres/::1/2020-08-08 16:38:22.730158-04');


--
-- Data for Name: ciudades; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ciudades (id_ciu, ciu_descrip, id_pais, estado, auditoria) VALUES (5, 'SÃO PAULO', 3, 'ACTIVO', 'INSERCION/administrador/2020-08-28 22:09:44.848429-04INACTIVACION/administrador/2020-09-02 20:53:33.317842-04ACTIVACION/administrador/2020-09-02 20:53:34.071107-04MODIFICACION/usuario1/2020-09-23 10:07:56.340469-04');
INSERT INTO ciudades (id_ciu, ciu_descrip, id_pais, estado, auditoria) VALUES (6, 'BUENOS AIRES', 2, 'ACTIVO', 'INSERCION/administrador/2020-08-28 22:10:46.352337-04INACTIVACION/administrador/2020-09-02 20:33:09.913906-04ACTIVACION/administrador/2020-09-02 20:33:13.047991-04MODIFICACION/usuario1/2020-09-23 10:08:15.547703-04');
INSERT INTO ciudades (id_ciu, ciu_descrip, id_pais, estado, auditoria) VALUES (2, 'YPANE', 1, 'ACTIVO', 'ctorresINACTIVACION/administrador/2020-09-02 20:53:27.19972-04ACTIVACION/administrador/2020-09-02 20:53:28.162819-04');
INSERT INTO ciudades (id_ciu, ciu_descrip, id_pais, estado, auditoria) VALUES (3, 'FERNANDO DE LA MORA', 1, 'ACTIVO', 'ctorresINACTIVACION/administrador/2020-09-02 20:33:14.023076-04ACTIVACION/administrador/2020-09-02 20:33:14.880688-04INACTIVACION/administrador/2020-09-02 20:53:28.920329-04ACTIVACION/administrador/2020-09-02 20:53:29.590307-04');
INSERT INTO ciudades (id_ciu, ciu_descrip, id_pais, estado, auditoria) VALUES (4, 'LOMA PYTA', 1, 'ACTIVO', 'ctorresINACTIVACION/administrador/2020-09-02 20:33:15.832148-04ACTIVACION/administrador/2020-09-02 20:33:16.521976-04INACTIVACION/administrador/2020-09-02 20:53:30.273868-04ACTIVACION/administrador/2020-09-02 20:53:30.985794-04');
INSERT INTO ciudades (id_ciu, ciu_descrip, id_pais, estado, auditoria) VALUES (1, 'ÑEMBY', 1, 'ACTIVO', 'ctorresINACTIVACION/administrador/2020-09-02 20:33:01.411803-04ACTIVACION/administrador/2020-09-02 20:33:02.431117-04INACTIVACION/administrador/2020-09-02 20:33:03.296488-04ACTIVACION/administrador/2020-09-02 20:33:04.023886-04INACTIVACION/administrador/2020-09-02 20:33:04.69939-04ACTIVACION/administrador/2020-09-02 20:33:05.517562-04INACTIVACION/administrador/2020-09-02 20:33:06.182702-04ACTIVACION/administrador/2020-09-02 20:33:06.942349-04INACTIVACION/administrador/2020-09-02 20:35:00.214028-04ACTIVACION/administrador/2020-09-02 20:35:01.034289-04INACTIVACION/administrador/2020-09-02 20:35:01.929671-04ACTIVACION/administrador/2020-09-02 20:35:02.438551-04INACTIVACION/administrador/2020-09-02 20:35:03.112344-04ACTIVACION/administrador/2020-09-02 20:35:04.18374-04INACTIVACION/administrador/2020-09-02 20:53:25.234718-04ACTIVACION/administrador/2020-09-02 20:53:26.22304-04MODIFICACION/administrador/2020-09-02 21:07:30.557431-04MODIFICACION/administrador/2020-09-02 21:07:35.14541-04MODIFICACION/administrador/2020-09-02 21:13:34.537051-04MODIFICACION/administrador/2020-09-02 21:13:40.779721-04MODIFICACION/administrador/2020-09-02 21:13:45.98479-04INACTIVACION/usuario1/2020-09-12 13:09:28.936495-04ACTIVACION/usuario1/2020-09-12 13:09:29.881721-04MODIFICACION/usuario1/2020-11-07 09:19:55.756341-03MODIFICACION/usuario1/2020-11-07 09:20:04.491616-03MODIFICACION/usuario1/2020-11-07 09:20:24.731889-03');


--
-- Data for Name: clasificaciones; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO clasificaciones (id_cla, cla_descrip, estado, auditoria) VALUES (1, 'MATERIA PRIMA', 'ACTIVO', NULL);
INSERT INTO clasificaciones (id_cla, cla_descrip, estado, auditoria) VALUES (2, 'PRODUCTO', 'ACTIVO', NULL);


--
-- Data for Name: colores_cabecera; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO colores_cabecera (id_cc, cc_descrip) VALUES (1, 'main-header navbar navbar-expand border-bottom navbar-dark navbar-primary');
INSERT INTO colores_cabecera (id_cc, cc_descrip) VALUES (2, 'main-header navbar navbar-expand border-bottom navbar-dark navbar-secondary');
INSERT INTO colores_cabecera (id_cc, cc_descrip) VALUES (3, 'main-header navbar navbar-expand border-bottom navbar-dark navbar-info');
INSERT INTO colores_cabecera (id_cc, cc_descrip) VALUES (4, 'main-header navbar navbar-expand border-bottom navbar-dark navbar-success');
INSERT INTO colores_cabecera (id_cc, cc_descrip) VALUES (5, 'main-header navbar navbar-expand border-bottom navbar-dark navbar-danger');
INSERT INTO colores_cabecera (id_cc, cc_descrip) VALUES (6, 'main-header navbar navbar-expand border-bottom navbar-dark navbar-indigo');
INSERT INTO colores_cabecera (id_cc, cc_descrip) VALUES (7, 'main-header navbar navbar-expand border-bottom navbar-dark navbar-purple');
INSERT INTO colores_cabecera (id_cc, cc_descrip) VALUES (8, 'main-header navbar navbar-expand border-bottom navbar-dark navbar-pink');
INSERT INTO colores_cabecera (id_cc, cc_descrip) VALUES (9, 'main-header navbar navbar-expand border-bottom navbar-dark navbar-teal');
INSERT INTO colores_cabecera (id_cc, cc_descrip) VALUES (10, 'main-header navbar navbar-expand border-bottom navbar-dark navbar-cyan');
INSERT INTO colores_cabecera (id_cc, cc_descrip) VALUES (12, 'main-header navbar navbar-expand border-bottom navbar-dark navbar-gray-dark');
INSERT INTO colores_cabecera (id_cc, cc_descrip) VALUES (11, 'main-header navbar navbar-expand border-bottom navbar-dark');
INSERT INTO colores_cabecera (id_cc, cc_descrip) VALUES (13, 'main-header navbar navbar-expand border-bottom navbar-dark navbar-gray');
INSERT INTO colores_cabecera (id_cc, cc_descrip) VALUES (14, 'main-header navbar navbar-expand border-bottom navbar-light');
INSERT INTO colores_cabecera (id_cc, cc_descrip) VALUES (15, 'main-header navbar navbar-expand border-bottom navbar-light navbar-warning');
INSERT INTO colores_cabecera (id_cc, cc_descrip) VALUES (16, 'main-header navbar navbar-expand border-bottom navbar-light navbar-white');
INSERT INTO colores_cabecera (id_cc, cc_descrip) VALUES (17, 'main-header navbar navbar-expand border-bottom navbar-light navbar-orange');


--
-- Data for Name: colores_logo; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO colores_logo (id_cl, cl_descrip) VALUES (1, 'brand-link navbar-primary');
INSERT INTO colores_logo (id_cl, cl_descrip) VALUES (2, 'brand-link navbar-secondary');
INSERT INTO colores_logo (id_cl, cl_descrip) VALUES (3, 'brand-link navbar-info');
INSERT INTO colores_logo (id_cl, cl_descrip) VALUES (4, 'brand-link navbar-success');
INSERT INTO colores_logo (id_cl, cl_descrip) VALUES (5, 'brand-link navbar-danger');
INSERT INTO colores_logo (id_cl, cl_descrip) VALUES (6, 'brand-link navbar-indigo');
INSERT INTO colores_logo (id_cl, cl_descrip) VALUES (7, 'brand-link navbar-purple');
INSERT INTO colores_logo (id_cl, cl_descrip) VALUES (8, 'brand-link navbar-pink');
INSERT INTO colores_logo (id_cl, cl_descrip) VALUES (9, 'brand-link navbar-teal');
INSERT INTO colores_logo (id_cl, cl_descrip) VALUES (10, 'brand-link navbar-cyan');
INSERT INTO colores_logo (id_cl, cl_descrip) VALUES (11, 'brand-link navbar-dark');
INSERT INTO colores_logo (id_cl, cl_descrip) VALUES (12, 'brand-link navbar-gray-dark');
INSERT INTO colores_logo (id_cl, cl_descrip) VALUES (13, 'brand-link navbar-gray');
INSERT INTO colores_logo (id_cl, cl_descrip) VALUES (14, 'brand-link navbar-light');
INSERT INTO colores_logo (id_cl, cl_descrip) VALUES (15, 'brand-link navbar-warning');
INSERT INTO colores_logo (id_cl, cl_descrip) VALUES (16, 'brand-link navbar-white');
INSERT INTO colores_logo (id_cl, cl_descrip) VALUES (17, 'brand-link navbar-orange');


--
-- Data for Name: colores_menu; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO colores_menu (id_cm, cm_descrip) VALUES (1, 'main-sidebar elevation-4 sidebar-dark-primary');
INSERT INTO colores_menu (id_cm, cm_descrip) VALUES (2, 'main-sidebar elevation-4 sidebar-dark-warning');
INSERT INTO colores_menu (id_cm, cm_descrip) VALUES (3, 'main-sidebar elevation-4 sidebar-dark-info');
INSERT INTO colores_menu (id_cm, cm_descrip) VALUES (4, 'main-sidebar elevation-4 sidebar-dark-danger');
INSERT INTO colores_menu (id_cm, cm_descrip) VALUES (5, 'main-sidebar elevation-4 sidebar-dark-success');
INSERT INTO colores_menu (id_cm, cm_descrip) VALUES (6, 'main-sidebar elevation-4 sidebar-light-primary');
INSERT INTO colores_menu (id_cm, cm_descrip) VALUES (7, 'main-sidebar elevation-4 sidebar-light-warning');
INSERT INTO colores_menu (id_cm, cm_descrip) VALUES (8, 'main-sidebar elevation-4 sidebar-light-info');
INSERT INTO colores_menu (id_cm, cm_descrip) VALUES (9, 'main-sidebar elevation-4 sidebar-light-danger');
INSERT INTO colores_menu (id_cm, cm_descrip) VALUES (10, 'main-sidebar elevation-4 sidebar-light-success');


--
-- Data for Name: estados_civiles; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO estados_civiles (id_ec, ec_descrip, estado, auditoria) VALUES (1, 'SOLTERO', 'ACTIVO', 'ctorres');
INSERT INTO estados_civiles (id_ec, ec_descrip, estado, auditoria) VALUES (2, 'CASADO', 'ACTIVO', 'ctorres');
INSERT INTO estados_civiles (id_ec, ec_descrip, estado, auditoria) VALUES (3, 'VIUDO', 'ACTIVO', 'ctorres');
INSERT INTO estados_civiles (id_ec, ec_descrip, estado, auditoria) VALUES (4, 'DIVORCIADO', 'ACTIVO', 'ctorres');


--
-- Data for Name: funcionarios; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO funcionarios (id_fun, id_per, id_car, estado, auditoria) VALUES (2, 2, 1, 'ACTIVO', 'ctorres');
INSERT INTO funcionarios (id_fun, id_per, id_car, estado, auditoria) VALUES (4, 4, 1, 'ACTIVO', 'ctorres');
INSERT INTO funcionarios (id_fun, id_per, id_car, estado, auditoria) VALUES (1, 1, 2, 'ACTIVO', 'ctorres');
INSERT INTO funcionarios (id_fun, id_per, id_car, estado, auditoria) VALUES (3, 3, 2, 'ACTIVO', 'ctorres');


--
-- Data for Name: generos; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO generos (id_gen, gen_descrip, estado, auditoria) VALUES (1, 'MASCULINO', 'ACTIVO', 'ctorres');
INSERT INTO generos (id_gen, gen_descrip, estado, auditoria) VALUES (2, 'FEMENINO', 'ACTIVO', 'ctorres');


--
-- Data for Name: grupos; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO grupos (id_gru, gru_descrip, estado, auditoria) VALUES (1, 'ADMINISTRADOR', 'ACTIVO', 'ctorres');
INSERT INTO grupos (id_gru, gru_descrip, estado, auditoria) VALUES (3, 'RECEPCIONISTA', 'ACTIVO', 'ctorres');
INSERT INTO grupos (id_gru, gru_descrip, estado, auditoria) VALUES (2, 'ENCARGADO DE COMPRA', 'ACTIVO', 'ctorres');


--
-- Data for Name: modulos; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO modulos (id_mod, mod_descrip, estado, auditoria, icono) VALUES (1, 'CONFIGURACION', 'ACTIVO', 'ctorres', 'fas fa-cog');
INSERT INTO modulos (id_mod, mod_descrip, estado, auditoria, icono) VALUES (3, 'ARCHIVO', 'ACTIVO', 'ctorres', 'fas fa-clipboard-list');
INSERT INTO modulos (id_mod, mod_descrip, estado, auditoria, icono) VALUES (2, 'MIS RECURSOS', 'ACTIVO', 'ctorres', 'fas fa-users-cog');
INSERT INTO modulos (id_mod, mod_descrip, estado, auditoria, icono) VALUES (4, 'COMPRAS', 'ACTIVO', 'ctorres', 'fas fa-user');


--
-- Data for Name: paginas; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO paginas (id_pag, estado, auditoria, pag_descrip, pag_ubicacion, id_mod, icono) VALUES (4, 'ACTIVO', 'ctorres', 'PEDIDO DE COMPRA', '/tercero/compra/pedido', 4, 'fas fa-people-carry');
INSERT INTO paginas (id_pag, estado, auditoria, pag_descrip, pag_ubicacion, id_mod, icono) VALUES (3, 'ACTIVO', 'ctorres', 'PERSONAS', '/tercero/archivo/personas', 3, 'fas fa-users');
INSERT INTO paginas (id_pag, estado, auditoria, pag_descrip, pag_ubicacion, id_mod, icono) VALUES (5, 'ACTIVO', 'ctorres', 'ORDEN DE COMPRA', '/tercero/compra/orden', 4, 'fa fa-shopping-cart');
INSERT INTO paginas (id_pag, estado, auditoria, pag_descrip, pag_ubicacion, id_mod, icono) VALUES (6, 'ACTIVO', 'ctorres', 'FACTURA DE COMPRA', '/tercero/compra/factura', 4, 'fa fa-cart-plus');
INSERT INTO paginas (id_pag, estado, auditoria, pag_descrip, pag_ubicacion, id_mod, icono) VALUES (2, 'ACTIVO', 'ctorres', 'CIUDADES', '/tercero/archivo/ciudades', 3, 'fas fa-globe-americas');
INSERT INTO paginas (id_pag, estado, auditoria, pag_descrip, pag_ubicacion, id_mod, icono) VALUES (7, 'ACTIVO', 'ctorres', 'NOTA DE CREDITO Y DEBITO', '/tercero/compra/credito', 4, 'fa fa-file');
INSERT INTO paginas (id_pag, estado, auditoria, pag_descrip, pag_ubicacion, id_mod, icono) VALUES (1, 'ACTIVO', 'ctorres', 'TIPO DE IMPUESTO', '/tercero/archivo/impuestos', 3, 'fas fa-balance-scale-right');


--
-- Data for Name: paises; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO paises (id_pais, pais_descrip, gentilicio, estado, auditoria, pais_abreviatura) VALUES (1, 'PARAGUAY', 'PARAGUAYA', 'ACTIVO', 'ctorres', 'PY');
INSERT INTO paises (id_pais, pais_descrip, gentilicio, estado, auditoria, pais_abreviatura) VALUES (2, 'ARGENTINA', 'ARGENTINA', 'ACTIVO', NULL, NULL);
INSERT INTO paises (id_pais, pais_descrip, gentilicio, estado, auditoria, pais_abreviatura) VALUES (3, 'BRASIL', 'BRASIL', 'ACTIVO', NULL, NULL);


--
-- Data for Name: permisos; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO permisos (id_pag, id_ac, id_gru, estado, auditoria) VALUES (1, 1, 1, 'ACTIVO', 'ctorres');
INSERT INTO permisos (id_pag, id_ac, id_gru, estado, auditoria) VALUES (2, 1, 1, 'ACTIVO', 'ctorres');
INSERT INTO permisos (id_pag, id_ac, id_gru, estado, auditoria) VALUES (3, 1, 1, 'ACTIVO', 'ctorres');
INSERT INTO permisos (id_pag, id_ac, id_gru, estado, auditoria) VALUES (4, 1, 1, 'ACTIVO', 'ctorres');
INSERT INTO permisos (id_pag, id_ac, id_gru, estado, auditoria) VALUES (5, 1, 1, 'ACTIVO', 'ctorres');
INSERT INTO permisos (id_pag, id_ac, id_gru, estado, auditoria) VALUES (6, 1, 1, 'ACTIVO', 'ctorres');
INSERT INTO permisos (id_pag, id_ac, id_gru, estado, auditoria) VALUES (7, 1, 1, 'ACTIVO', 'ctorres');


--
-- Data for Name: personas; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO personas (id_per, per_ci, per_ruc, per_nombre, per_apellido, per_fenaci, per_celular, per_email, per_direccion, id_ciu, id_gen, id_ec, estado, auditoria) VALUES (5, '524613', '524613', 'ANDRES', 'FERNANDEZ', '1996-11-02', '0985526589', 'christiantorres.cdt@gmail.com', 'CALLE SAN ROQUE, BARRIO MARIA AUXILIADORA, PAI ÑU', 3, 1, 2, 'ACTIVO', 'INSERCION/administrador/2020-08-14 19:33:32.014022-04MODIFICACION/administrador/2020-08-14 19:38:15.380031-04MODIFICACION/administrador/2020-08-14 19:38:39.865551-04MODIFICACION/administrador/2020-08-14 19:38:56.032707-04MODIFICACION/administrador/2020-08-14 19:39:14.068267-04');
INSERT INTO personas (id_per, per_ci, per_ruc, per_nombre, per_apellido, per_fenaci, per_celular, per_email, per_direccion, id_ciu, id_gen, id_ec, estado, auditoria) VALUES (4, '134652', '134652', 'BENITO', 'PEREIRA', '1996-11-02', '0985526589', 'christiantorres.cdt@gmail.com', 'CALLE SAN ROQUE, BARRIO MARIA AUXILIADORA, PAI ÑU', 1, 1, 2, 'ACTIVO', 'ctorres');
INSERT INTO personas (id_per, per_ci, per_ruc, per_nombre, per_apellido, per_fenaci, per_celular, per_email, per_direccion, id_ciu, id_gen, id_ec, estado, auditoria) VALUES (3, '654321', '654321', 'SERGIO', 'GONZALEZ', '1996-11-02', '0985526589', 'christiantorres.cdt@gmail.com', 'CALLE SAN ROQUE, BARRIO MARIA AUXILIADORA, PAI ÑU', 1, 1, 1, 'ACTIVO', 'ctorresACTIVACION/administrador/2020-08-14 19:48:35.474514-04INACTIVACION/administrador/2020-08-14 19:48:36.611621-04ACTIVACION/administrador/2020-09-02 21:50:48.736412-04');
INSERT INTO personas (id_per, per_ci, per_ruc, per_nombre, per_apellido, per_fenaci, per_celular, per_email, per_direccion, id_ciu, id_gen, id_ec, estado, auditoria) VALUES (1, '4580373-0', '4580373-0', 'CHRISTIAN DAVID', 'TORRES', '1996-11-02', '0985526589', 'christiantorres.cdt@gmail.com', 'CALLE SAN ROQUE, BARRIO MARIA AUXILIADORA, PAI ÑU', 1, 1, 2, 'ACTIVO', 'ctorresINACTIVACION/administrador/2020-08-14 19:41:59.098429-04ACTIVACION/administrador/2020-08-14 19:42:05.928578-04MODIFICACION/administrador/2020-08-14 19:48:33.842215-04INACTIVACION/administrador/2020-09-02 21:50:45.601531-04ACTIVACION/administrador/2020-09-02 21:50:47.010308-04MODIFICACION/administrador/2020-09-02 21:50:55.808475-04INACTIVACION/administrador/2020-09-02 21:51:07.528739-04ACTIVACION/administrador/2020-09-02 21:51:08.473834-04INACTIVACION/administrador/2020-09-02 21:51:10.430483-04ACTIVACION/administrador/2020-09-02 21:51:11.305119-04');
INSERT INTO personas (id_per, per_ci, per_ruc, per_nombre, per_apellido, per_fenaci, per_celular, per_email, per_direccion, id_ciu, id_gen, id_ec, estado, auditoria) VALUES (2, '123456', '123456', 'JUAN', 'PEREZ', '1996-11-02', '0985526589', 'christiantorres.cdt@gmail.com', 'CALLE SAN ROQUE, BARRIO MARIA AUXILIADORA, PAI ÑU', 1, 1, 2, 'ACTIVO', 'ctorresINACTIVACION/administrador/2020-09-02 21:50:44.694804-04ACTIVACION/administrador/2020-09-02 21:50:47.804432-04INACTIVACION/administrador/2020-09-02 21:51:09.493457-04ACTIVACION/administrador/2020-09-02 21:51:12.100564-04');


--
-- Data for Name: sucursales; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO sucursales (id_suc, suc_ruc, suc_nombre, suc_email, suc_celular, suc_direccion, suc_ubicacion, estado, auditoria, suc_imagen, id_ciu, suc_abreviacion) VALUES (2, '4580373-0', 'ÑEMBY', 'graficos.cdt.s2@gmail.com', '0985526589', 'calle san roque', '-x -y', 'ACTIVO', 'ctorres', '/tercero/imagenes/sucursales/2.jpg', 2, 'ÑEMBY');
INSERT INTO sucursales (id_suc, suc_ruc, suc_nombre, suc_email, suc_celular, suc_direccion, suc_ubicacion, estado, auditoria, suc_imagen, id_ciu, suc_abreviacion) VALUES (3, '4580373-0', 'VILLA ELISA', 'graficos.cdt.s3@gmail.com', '0985526589', 'calle san roque', '-x -y', 'ACTIVO', 'ctorres', '/tercero/imagenes/sucursales/3.jpg', 3, 'VILLA ELISA');
INSERT INTO sucursales (id_suc, suc_ruc, suc_nombre, suc_email, suc_celular, suc_direccion, suc_ubicacion, estado, auditoria, suc_imagen, id_ciu, suc_abreviacion) VALUES (4, '4580373-0', 'SAN LORENZO', 'graficos.cdt.s4@gmail.com', '0985526589', 'calle san roque', '-x -y', 'ACTIVO', 'ctorres', '/tercero/imagenes/sucursales/4.jpg', 4, 'SAN LORENZO');
INSERT INTO sucursales (id_suc, suc_ruc, suc_nombre, suc_email, suc_celular, suc_direccion, suc_ubicacion, estado, auditoria, suc_imagen, id_ciu, suc_abreviacion) VALUES (5, '4580373-0', 'FERNANDO DE LA MORA', 'graficos.cdt.s5@gmail.com', '0985526589', 'calle san roque', '-x -y', 'ACTIVO', 'ctorres', '/tercero/imagenes/sucursales/5.jpg', 5, 'FERNANDO DE LA MORA');
INSERT INTO sucursales (id_suc, suc_ruc, suc_nombre, suc_email, suc_celular, suc_direccion, suc_ubicacion, estado, auditoria, suc_imagen, id_ciu, suc_abreviacion) VALUES (1, '4580373-0', 'CASA MATRIZ', 'graficos.cdt@gmail.com', '0985526589', 'calle san roque', '-x -y', 'ACTIVO', 'ctorres', '/tercero/imagenes/sucursales/1.jpg', 1, 'CASA MATRIZ');


--
-- Data for Name: usuario_sucursal; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO usuario_sucursal (id_usu, id_suc, estado, auditoria) VALUES (1, 1, 'ACTIVO', 'ctorres');
INSERT INTO usuario_sucursal (id_usu, id_suc, estado, auditoria) VALUES (1, 2, 'ACTIVO', 'ctorres');
INSERT INTO usuario_sucursal (id_usu, id_suc, estado, auditoria) VALUES (1, 3, 'ACTIVO', 'ctorres');
INSERT INTO usuario_sucursal (id_usu, id_suc, estado, auditoria) VALUES (1, 4, 'ACTIVO', 'ctorres');
INSERT INTO usuario_sucursal (id_usu, id_suc, estado, auditoria) VALUES (1, 5, 'ACTIVO', 'ctorres');


--
-- Data for Name: usuarios; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO usuarios (id_usu, usu_nombre, usu_contrasena, ultima_sesion, id_gru, id_fun, auditoria, estado, id_suc, usu_imagen, id_cc, id_cl, id_cm) VALUES (2, 'usuario2', '202cb962ac59075b964b07152d234b70', '::1 2020-09-17 20:18:47.133685-04', 2, 2, 'ctorres', 'ACTIVO', 1, '/tercero/imagenes/usuarios/2.png', 2, 2, 2);
INSERT INTO usuarios (id_usu, usu_nombre, usu_contrasena, ultima_sesion, id_gru, id_fun, auditoria, estado, id_suc, usu_imagen, id_cc, id_cl, id_cm) VALUES (3, 'usuario3', '202cb962ac59075b964b07152d234b70', '::1 2020-08-14 20:12:57.313165-04', 3, 3, 'ctorres', 'ACTIVO', 1, '/tercero/imagenes/usuarios/3.png', 3, 3, 8);
INSERT INTO usuarios (id_usu, usu_nombre, usu_contrasena, ultima_sesion, id_gru, id_fun, auditoria, estado, id_suc, usu_imagen, id_cc, id_cl, id_cm) VALUES (4, 'usuario4', '202cb962ac59075b964b07152d234b70', '::1 2020-08-14 20:13:21.602765-04', 2, 4, 'ctorres', 'ACTIVO', 1, '/tercero/imagenes/usuarios/4.png', 5, 5, 9);
INSERT INTO usuarios (id_usu, usu_nombre, usu_contrasena, ultima_sesion, id_gru, id_fun, auditoria, estado, id_suc, usu_imagen, id_cc, id_cl, id_cm) VALUES (1, 'usuario1', '202cb962ac59075b964b07152d234b70', '::1 2020-11-25 17:32:27.355321-03', 1, 1, 'ctorres', 'ACTIVO', 1, '/tercero/imagenes/usuarios/1.png', 1, 1, 7);


--
-- Name: acciones_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY acciones
    ADD CONSTRAINT acciones_pk PRIMARY KEY (id_ac);


--
-- Name: cargos_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY cargos
    ADD CONSTRAINT cargos_pk PRIMARY KEY (id_car);


--
-- Name: ciudades_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ciudades
    ADD CONSTRAINT ciudades_pkey PRIMARY KEY (id_ciu);


--
-- Name: clasificaciones_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY clasificaciones
    ADD CONSTRAINT clasificaciones_pkey PRIMARY KEY (id_cla);


--
-- Name: colores_cabecera_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY colores_cabecera
    ADD CONSTRAINT colores_cabecera_pkey PRIMARY KEY (id_cc);


--
-- Name: colores_logo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY colores_logo
    ADD CONSTRAINT colores_logo_pkey PRIMARY KEY (id_cl);


--
-- Name: colores_menu_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY colores_menu
    ADD CONSTRAINT colores_menu_pkey PRIMARY KEY (id_cm);


--
-- Name: estados_civiles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY estados_civiles
    ADD CONSTRAINT estados_civiles_pkey PRIMARY KEY (id_ec);


--
-- Name: funcionarios_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY funcionarios
    ADD CONSTRAINT funcionarios_pk PRIMARY KEY (id_fun);


--
-- Name: generos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY generos
    ADD CONSTRAINT generos_pkey PRIMARY KEY (id_gen);


--
-- Name: grupos_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupos
    ADD CONSTRAINT grupos_pk PRIMARY KEY (id_gru);


--
-- Name: modulos_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY modulos
    ADD CONSTRAINT modulos_pk PRIMARY KEY (id_mod);


--
-- Name: paginas_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY paginas
    ADD CONSTRAINT paginas_pk PRIMARY KEY (id_pag);


--
-- Name: paises_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY paises
    ADD CONSTRAINT paises_pkey PRIMARY KEY (id_pais);


--
-- Name: permisos_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY permisos
    ADD CONSTRAINT permisos_pk PRIMARY KEY (id_pag, id_ac, id_gru);


--
-- Name: personas_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY personas
    ADD CONSTRAINT personas_pk PRIMARY KEY (id_per);


--
-- Name: sucursales_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY sucursales
    ADD CONSTRAINT sucursales_pk PRIMARY KEY (id_suc);


--
-- Name: usuario_sucursal_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuario_sucursal
    ADD CONSTRAINT usuario_sucursal_pk PRIMARY KEY (id_usu, id_suc);


--
-- Name: usuarios_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuarios
    ADD CONSTRAINT usuarios_pk PRIMARY KEY (id_usu);


--
-- Name: acciones_permisos_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY permisos
    ADD CONSTRAINT acciones_permisos_fk FOREIGN KEY (id_ac) REFERENCES acciones(id_ac);


--
-- Name: cargos_funcionarios_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY funcionarios
    ADD CONSTRAINT cargos_funcionarios_fk FOREIGN KEY (id_car) REFERENCES cargos(id_car);


--
-- Name: ciudades_id_pais_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ciudades
    ADD CONSTRAINT ciudades_id_pais_fkey FOREIGN KEY (id_pais) REFERENCES paises(id_pais);


--
-- Name: estados_civiles_personas_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY personas
    ADD CONSTRAINT estados_civiles_personas_fk FOREIGN KEY (id_ec) REFERENCES estados_civiles(id_ec);


--
-- Name: funcionarios_usuarios_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuarios
    ADD CONSTRAINT funcionarios_usuarios_fk FOREIGN KEY (id_fun) REFERENCES funcionarios(id_fun);


--
-- Name: generos_personas_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY personas
    ADD CONSTRAINT generos_personas_fk FOREIGN KEY (id_gen) REFERENCES generos(id_gen);


--
-- Name: grupos_permisos_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY permisos
    ADD CONSTRAINT grupos_permisos_fk FOREIGN KEY (id_gru) REFERENCES grupos(id_gru);


--
-- Name: grupos_usuarios_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuarios
    ADD CONSTRAINT grupos_usuarios_fk FOREIGN KEY (id_gru) REFERENCES grupos(id_gru);


--
-- Name: modulos_paginas_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY paginas
    ADD CONSTRAINT modulos_paginas_fk FOREIGN KEY (id_mod) REFERENCES modulos(id_mod);


--
-- Name: paginas_permisos_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY permisos
    ADD CONSTRAINT paginas_permisos_fk FOREIGN KEY (id_pag) REFERENCES paginas(id_pag);


--
-- Name: paises_ciudades_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ciudades
    ADD CONSTRAINT paises_ciudades_fk FOREIGN KEY (id_pais) REFERENCES paises(id_pais);


--
-- Name: personas_funcionarios_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY funcionarios
    ADD CONSTRAINT personas_funcionarios_fk FOREIGN KEY (id_per) REFERENCES personas(id_per);


--
-- Name: sucursales_usuario_sucursal_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuario_sucursal
    ADD CONSTRAINT sucursales_usuario_sucursal_fk FOREIGN KEY (id_suc) REFERENCES sucursales(id_suc);


--
-- Name: sucursales_usuarios_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuarios
    ADD CONSTRAINT sucursales_usuarios_fk FOREIGN KEY (id_suc) REFERENCES sucursales(id_suc);


--
-- Name: usuarios_colores_cabecera_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuarios
    ADD CONSTRAINT usuarios_colores_cabecera_fk FOREIGN KEY (id_cc) REFERENCES colores_cabecera(id_cc);


--
-- Name: usuarios_colores_logo_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuarios
    ADD CONSTRAINT usuarios_colores_logo_fk FOREIGN KEY (id_cl) REFERENCES colores_logo(id_cl);


--
-- Name: usuarios_colores_menu_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuarios
    ADD CONSTRAINT usuarios_colores_menu_fk FOREIGN KEY (id_cm) REFERENCES colores_menu(id_cm);


--
-- Name: usuarios_usuario_sucursal_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuario_sucursal
    ADD CONSTRAINT usuarios_usuario_sucursal_fk FOREIGN KEY (id_usu) REFERENCES usuarios(id_usu);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

