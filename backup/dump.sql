--
-- PostgreSQL database dump
--

-- Dumped from database version 17.5
-- Dumped by pg_dump version 17.4

-- Started on 2025-05-28 13:05:08

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 2 (class 3079 OID 16658)
-- Name: uuid-ossp; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS "uuid-ossp" WITH SCHEMA public;


--
-- TOC entry 5075 (class 0 OID 0)
-- Dependencies: 2
-- Name: EXTENSION "uuid-ossp"; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION "uuid-ossp" IS 'generate universally unique identifiers (UUIDs)';


--
-- TOC entry 254 (class 1255 OID 16841)
-- Name: atualizar_atualizado_em(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.atualizar_atualizado_em() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.updated_at = now();
   RETURN NEW;
END;
$$;


ALTER FUNCTION public.atualizar_atualizado_em() OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 237 (class 1259 OID 17035)
-- Name: cache; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache OWNER TO postgres;

--
-- TOC entry 238 (class 1259 OID 17042)
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO postgres;

--
-- TOC entry 225 (class 1259 OID 16779)
-- Name: cupons_desconto; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cupons_desconto (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    evento_id uuid NOT NULL,
    codigo character varying(30) NOT NULL,
    descricao character varying(100),
    percentual_desconto numeric(5,2),
    maximo_usos integer,
    usos_efetuados integer DEFAULT 0,
    data_inicio timestamp without time zone NOT NULL,
    data_fim timestamp without time zone NOT NULL,
    criado_em timestamp without time zone DEFAULT now(),
    atualizado_em timestamp without time zone DEFAULT now(),
    CONSTRAINT cupons_desconto_maximo_usos_check CHECK ((maximo_usos >= 0)),
    CONSTRAINT cupons_desconto_percentual_desconto_check CHECK (((percentual_desconto >= (0)::numeric) AND (percentual_desconto <= (100)::numeric)))
);


ALTER TABLE public.cupons_desconto OWNER TO postgres;

--
-- TOC entry 234 (class 1259 OID 16974)
-- Name: produtores; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.produtores (
    id integer NOT NULL,
    nome_empresa character varying(100) NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone DEFAULT now(),
    usuario_id integer
);


ALTER TABLE public.produtores OWNER TO postgres;

--
-- TOC entry 233 (class 1259 OID 16973)
-- Name: produtores_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.produtores_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.produtores_id_seq OWNER TO postgres;

--
-- TOC entry 5076 (class 0 OID 0)
-- Dependencies: 233
-- Name: produtores_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.produtores_id_seq OWNED BY public.produtores.id;


--
-- TOC entry 221 (class 1259 OID 16713)
-- Name: eventos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.eventos (
    nome character varying(100) NOT NULL,
    descricao text,
    banner_url text NOT NULL,
    data_evento timestamp without time zone NOT NULL,
    localizacao character varying(150) NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone DEFAULT now(),
    produtor_id character varying,
    id bigint DEFAULT nextval('public.produtores_id_seq'::regclass) NOT NULL
);


ALTER TABLE public.eventos OWNER TO postgres;

--
-- TOC entry 243 (class 1259 OID 17067)
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO postgres;

--
-- TOC entry 242 (class 1259 OID 17066)
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.failed_jobs_id_seq OWNER TO postgres;

--
-- TOC entry 5077 (class 0 OID 0)
-- Dependencies: 242
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- TOC entry 224 (class 1259 OID 16758)
-- Name: ingressos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ingressos (
    codigo character varying(50) NOT NULL,
    status character varying(20) NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone DEFAULT now(),
    lote_id bigint,
    usuario_id bigint,
    id bigint DEFAULT nextval('public.produtores_id_seq'::regclass) NOT NULL,
    CONSTRAINT ingressos_status_check CHECK (((status)::text = ANY ((ARRAY['pendente'::character varying, 'pago'::character varying, 'cancelado'::character varying])::text[])))
);


ALTER TABLE public.ingressos OWNER TO postgres;

--
-- TOC entry 241 (class 1259 OID 17059)
-- Name: job_batches; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


ALTER TABLE public.job_batches OWNER TO postgres;

--
-- TOC entry 240 (class 1259 OID 17050)
-- Name: jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE public.jobs OWNER TO postgres;

--
-- TOC entry 239 (class 1259 OID 17049)
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jobs_id_seq OWNER TO postgres;

--
-- TOC entry 5078 (class 0 OID 0)
-- Dependencies: 239
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- TOC entry 223 (class 1259 OID 16743)
-- Name: lotes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.lotes (
    nome character varying(50) NOT NULL,
    preco numeric(10,2) NOT NULL,
    quantidade integer NOT NULL,
    data_inicio timestamp without time zone NOT NULL,
    data_fim timestamp without time zone NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone DEFAULT now(),
    setor_id bigint,
    id bigint DEFAULT nextval('public.produtores_id_seq'::regclass) NOT NULL,
    CONSTRAINT lotes_preco_check CHECK ((preco >= (0)::numeric)),
    CONSTRAINT lotes_quantidade_check CHECK ((quantidade >= 0))
);


ALTER TABLE public.lotes OWNER TO postgres;

--
-- TOC entry 230 (class 1259 OID 16849)
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- TOC entry 229 (class 1259 OID 16848)
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO postgres;

--
-- TOC entry 5079 (class 0 OID 0)
-- Dependencies: 229
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- TOC entry 228 (class 1259 OID 16825)
-- Name: notificacoes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.notificacoes (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    usuario_id uuid,
    titulo character varying(100) NOT NULL,
    mensagem text NOT NULL,
    enviado_em timestamp without time zone DEFAULT now(),
    canal character varying(20) NOT NULL,
    criado_em timestamp without time zone DEFAULT now(),
    CONSTRAINT notificacoes_canal_check CHECK (((canal)::text = ANY ((ARRAY['email'::character varying, 'sms'::character varying])::text[])))
);


ALTER TABLE public.notificacoes OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 16797)
-- Name: pagamentos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.pagamentos (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    ingresso_id uuid NOT NULL,
    metodo_pagamento character varying(20) NOT NULL,
    status character varying(20) NOT NULL,
    transacao_id character varying(100),
    pix_qr_code text,
    pix_expiracao timestamp without time zone,
    criado_em timestamp without time zone DEFAULT now(),
    atualizado_em timestamp without time zone DEFAULT now(),
    CONSTRAINT pagamentos_metodo_pagamento_check CHECK (((metodo_pagamento)::text = 'pix'::text)),
    CONSTRAINT pagamentos_status_check CHECK (((status)::text = ANY ((ARRAY['pendente'::character varying, 'processando'::character varying, 'pago'::character varying, 'cancelado'::character varying, 'falhou'::character varying])::text[])))
);


ALTER TABLE public.pagamentos OWNER TO postgres;

--
-- TOC entry 231 (class 1259 OID 16866)
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 16670)
-- Name: perfis; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.perfis (
    id integer NOT NULL,
    nome character varying(30) NOT NULL,
    CONSTRAINT perfis_nome_check CHECK (((nome)::text = ANY ((ARRAY['admin'::character varying, 'produtor'::character varying, 'cliente'::character varying])::text[])))
);


ALTER TABLE public.perfis OWNER TO postgres;

--
-- TOC entry 218 (class 1259 OID 16669)
-- Name: perfis_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.perfis_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.perfis_id_seq OWNER TO postgres;

--
-- TOC entry 5080 (class 0 OID 0)
-- Dependencies: 218
-- Name: perfis_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.perfis_id_seq OWNED BY public.perfis.id;


--
-- TOC entry 232 (class 1259 OID 16873)
-- Name: sessions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO postgres;

--
-- TOC entry 222 (class 1259 OID 16728)
-- Name: setores; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.setores (
    nome character varying(50) NOT NULL,
    descricao text,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone DEFAULT now(),
    evento_id bigint,
    id bigint DEFAULT nextval('public.produtores_id_seq'::regclass) NOT NULL
);


ALTER TABLE public.setores OWNER TO postgres;

--
-- TOC entry 236 (class 1259 OID 16983)
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id integer NOT NULL,
    nome character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    senha character varying(255) NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone DEFAULT now(),
    telefone character varying,
    "cpf/cnpj" character varying
);


ALTER TABLE public.users OWNER TO postgres;

--
-- TOC entry 235 (class 1259 OID 16982)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO postgres;

--
-- TOC entry 5081 (class 0 OID 0)
-- Dependencies: 235
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- TOC entry 220 (class 1259 OID 16679)
-- Name: usuarios; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.usuarios (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    nome character varying(100) NOT NULL,
    email character varying(100) NOT NULL,
    telefone character varying(20) NOT NULL,
    cpf_cnpj character varying(18) NOT NULL,
    senha character varying(255) NOT NULL,
    perfil_id integer NOT NULL,
    criado_em timestamp without time zone DEFAULT now(),
    atualizado_em timestamp without time zone DEFAULT now()
);


ALTER TABLE public.usuarios OWNER TO postgres;

--
-- TOC entry 227 (class 1259 OID 16816)
-- Name: webhooks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.webhooks (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    payload jsonb NOT NULL,
    recebido_em timestamp without time zone DEFAULT now()
);


ALTER TABLE public.webhooks OWNER TO postgres;

--
-- TOC entry 4824 (class 2604 OID 17070)
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- TOC entry 4823 (class 2604 OID 17053)
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- TOC entry 4816 (class 2604 OID 16852)
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- TOC entry 4788 (class 2604 OID 16673)
-- Name: perfis id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.perfis ALTER COLUMN id SET DEFAULT nextval('public.perfis_id_seq'::regclass);


--
-- TOC entry 4817 (class 2604 OID 16977)
-- Name: produtores id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.produtores ALTER COLUMN id SET DEFAULT nextval('public.produtores_id_seq'::regclass);


--
-- TOC entry 4820 (class 2604 OID 16986)
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- TOC entry 5063 (class 0 OID 17035)
-- Dependencies: 237
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache (key, value, expiration) FROM stdin;
\.


--
-- TOC entry 5064 (class 0 OID 17042)
-- Dependencies: 238
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- TOC entry 5051 (class 0 OID 16779)
-- Dependencies: 225
-- Data for Name: cupons_desconto; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cupons_desconto (id, evento_id, codigo, descricao, percentual_desconto, maximo_usos, usos_efetuados, data_inicio, data_fim, criado_em, atualizado_em) FROM stdin;
77777777-7777-7777-7777-777777777777	44444444-4444-4444-4444-444444444444	PROMO10	Desconto de 10%	10.00	50	0	2025-06-01 00:00:00	2025-12-01 23:59:59	2025-05-25 15:46:43.745735	2025-05-25 15:46:43.745735
\.


--
-- TOC entry 5047 (class 0 OID 16713)
-- Dependencies: 221
-- Data for Name: eventos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.eventos (nome, descricao, banner_url, data_evento, localizacao, created_at, updated_at, produtor_id, id) FROM stdin;
Show Billie Eilish	Show massa	https://s3.aws.com/banners/festival.jpg	2025-11-03 00:00:00	Arena	2025-05-27 22:49:31	2025-05-27 22:49:31	2	3
\.


--
-- TOC entry 5069 (class 0 OID 17067)
-- Dependencies: 243
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- TOC entry 5050 (class 0 OID 16758)
-- Dependencies: 224
-- Data for Name: ingressos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ingressos (codigo, status, created_at, updated_at, lote_id, usuario_id, id) FROM stdin;
INGRESSO12345	pendente	2025-05-25 15:46:43.745735	2025-05-25 15:46:43.745735	\N	\N	9
\.


--
-- TOC entry 5067 (class 0 OID 17059)
-- Dependencies: 241
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- TOC entry 5066 (class 0 OID 17050)
-- Dependencies: 240
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- TOC entry 5049 (class 0 OID 16743)
-- Dependencies: 223
-- Data for Name: lotes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.lotes (nome, preco, quantidade, data_inicio, data_fim, created_at, updated_at, setor_id, id) FROM stdin;
Segundo lote	100.00	35	2025-05-27 18:40:00	2025-05-27 22:46:00	2025-05-27 23:40:42	2025-05-27 20:50:38.900215	7	8
\.


--
-- TOC entry 5056 (class 0 OID 16849)
-- Dependencies: 230
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
4	0001_01_01_000001_create_cache_table	2
5	0001_01_01_000002_create_jobs_table	2
\.


--
-- TOC entry 5054 (class 0 OID 16825)
-- Dependencies: 228
-- Data for Name: notificacoes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.notificacoes (id, usuario_id, titulo, mensagem, enviado_em, canal, criado_em) FROM stdin;
bbbbbbbb-bbbb-bbbb-bbbb-bbbbbbbbbbbb	22222222-2222-2222-2222-222222222222	Confirmação de Compra	Seu ingresso foi comprado com sucesso.	2025-05-25 15:46:43.745735	email	2025-05-25 15:46:43.745735
\.


--
-- TOC entry 5052 (class 0 OID 16797)
-- Dependencies: 226
-- Data for Name: pagamentos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.pagamentos (id, ingresso_id, metodo_pagamento, status, transacao_id, pix_qr_code, pix_expiracao, criado_em, atualizado_em) FROM stdin;
99999999-9999-9999-9999-999999999999	88888888-8888-8888-8888-888888888888	pix	pendente	TX123456789	00020101021226880014BR.GOV.BCB.PIX2563qrcode-pix.com.br/pix1234567890125204000053039865404200.005802BR5925Cliente Exemplo6009SAO PAULO61080540900062070503***6304ABCD	2025-06-01 23:59:59	2025-05-25 15:46:43.745735	2025-05-25 15:46:43.745735
\.


--
-- TOC entry 5057 (class 0 OID 16866)
-- Dependencies: 231
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- TOC entry 5045 (class 0 OID 16670)
-- Dependencies: 219
-- Data for Name: perfis; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.perfis (id, nome) FROM stdin;
1	admin
2	produtor
3	cliente
\.


--
-- TOC entry 5060 (class 0 OID 16974)
-- Dependencies: 234
-- Data for Name: produtores; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.produtores (id, nome_empresa, created_at, updated_at, usuario_id) FROM stdin;
2	AlieExpress	2025-05-27 03:14:39	2025-05-27 13:13:23	1
\.


--
-- TOC entry 5058 (class 0 OID 16873)
-- Dependencies: 232
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
JqV1XaWegfmc69yC0zAvqWdZ65JwwRkItDDI0Mfz	\N	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36	YTozOntzOjY6Il90b2tlbiI7czo0MDoiVEl0RFd4ZldtSWZXZW4yQmFnNm5YcmU3SVk5dnhGQXpJUXN1VnROTiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb3RlcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=	1748393514
36d1MbI2Bmmp6QqVncgtVAiM2i6BClHuVCPZCSMI	\N	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36	YToyOntzOjY6Il90b2tlbiI7czo0MDoiR05PME81eFE3UGFickdXQVc5c0dRdmV0WWtyT3A2NWNQNk5aRXBDVyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==	1748393638
nwAiC9cHcEwIZAvtz4xKXdf6vEjctWc1IqW0pdXq	\N	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36	YTozOntzOjY6Il90b2tlbiI7czo0MDoiUEUzTGtDNzZUZGJDQ0JVanNXQUZZZFNYcmVZT0EzZ2ZIRVZXMmdXbSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=	1748404788
lDJFIdHFxGrBBoPv2WnlGENjujUeZt3EeRVuM5Cz	\N	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36	YTozOntzOjY6Il90b2tlbiI7czo0MDoiTDJMNDdPSUF5TGlYbVNCQXJtOVRQM09vdjJPeUlCcWVaeXdGbmdrMiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=	1748445474
hiQa1VBl4fId348U9EJiXP8qe8nf3NnC2p7yfrIh	\N	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36	YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ1NLTXdIQnpJZGlUOUc3TDJ6YnJjVkRhZW1EcjJuWUx6alk3a1V4ciI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=	1748445868
\.


--
-- TOC entry 5048 (class 0 OID 16728)
-- Dependencies: 222
-- Data for Name: setores; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.setores (nome, descricao, created_at, updated_at, evento_id, id) FROM stdin;
Pista VIP	Open Bar até as 00:00	2025-05-27 22:55:42	2025-05-27 20:03:15.853159	3	7
\.


--
-- TOC entry 5062 (class 0 OID 16983)
-- Dependencies: 236
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, nome, email, senha, created_at, updated_at, telefone, "cpf/cnpj") FROM stdin;
1	LUANA DE MELO FRAGA	luana@gmail.com	$2y$12$WLtuTGTcGLdbYdeQvPC9l.P/5qlcX9VGRX9mvI1PW5aG4rSwoQn4u	2025-05-27 03:14:13	2025-05-27 03:14:13	75992978514	\N
\.


--
-- TOC entry 5046 (class 0 OID 16679)
-- Dependencies: 220
-- Data for Name: usuarios; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.usuarios (id, nome, email, telefone, cpf_cnpj, senha, perfil_id, criado_em, atualizado_em) FROM stdin;
a1fd34c3-ec7e-439c-af3d-345e8760b5a1	Admin Sistema	admin@sistema.com	11999999999	00000000000	$2y$10$7qbCpxJAXAgJu9aKk6lX4eRMB2whrO0RyxJP6z5rKugJwFPUWaRS.	1	2025-05-25 15:46:43.745735	2025-05-25 15:46:43.745735
11111111-1111-1111-1111-111111111111	Produtor de Eventos	produtor@sistema.com	11988887777	12345678000195	$2y$10$7qbCpxJAXAgJu9aKk6lX4eRMB2whrO0RyxJP6z5rKugJwFPUWaRS.	2	2025-05-25 15:46:43.745735	2025-05-25 15:46:43.745735
22222222-2222-2222-2222-222222222222	Cliente Exemplo	cliente@sistema.com	11977776666	98765432100	$2y$10$7qbCpxJAXAgJu9aKk6lX4eRMB2whrO0RyxJP6z5rKugJwFPUWaRS.	3	2025-05-25 15:46:43.745735	2025-05-25 15:46:43.745735
\.


--
-- TOC entry 5053 (class 0 OID 16816)
-- Dependencies: 227
-- Data for Name: webhooks; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.webhooks (id, payload, recebido_em) FROM stdin;
aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa	{"evento": "pagamento_confirmado", "ingresso": "INGRESSO12345"}	2025-05-25 15:46:43.745735
\.


--
-- TOC entry 5082 (class 0 OID 0)
-- Dependencies: 242
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- TOC entry 5083 (class 0 OID 0)
-- Dependencies: 239
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- TOC entry 5084 (class 0 OID 0)
-- Dependencies: 229
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 5, true);


--
-- TOC entry 5085 (class 0 OID 0)
-- Dependencies: 218
-- Name: perfis_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.perfis_id_seq', 3, true);


--
-- TOC entry 5086 (class 0 OID 0)
-- Dependencies: 233
-- Name: produtores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.produtores_id_seq', 11, true);


--
-- TOC entry 5087 (class 0 OID 0)
-- Dependencies: 235
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 1, true);


--
-- TOC entry 4882 (class 2606 OID 17048)
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- TOC entry 4880 (class 2606 OID 17041)
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- TOC entry 4856 (class 2606 OID 16791)
-- Name: cupons_desconto cupons_desconto_codigo_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cupons_desconto
    ADD CONSTRAINT cupons_desconto_codigo_key UNIQUE (codigo);


--
-- TOC entry 4858 (class 2606 OID 16789)
-- Name: cupons_desconto cupons_desconto_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cupons_desconto
    ADD CONSTRAINT cupons_desconto_pkey PRIMARY KEY (id);


--
-- TOC entry 4846 (class 2606 OID 17083)
-- Name: eventos eventos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.eventos
    ADD CONSTRAINT eventos_pkey PRIMARY KEY (id);


--
-- TOC entry 4889 (class 2606 OID 17075)
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 4891 (class 2606 OID 17077)
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- TOC entry 4852 (class 2606 OID 16768)
-- Name: ingressos ingressos_codigo_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ingressos
    ADD CONSTRAINT ingressos_codigo_key UNIQUE (codigo);


--
-- TOC entry 4854 (class 2606 OID 17112)
-- Name: ingressos ingressos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ingressos
    ADD CONSTRAINT ingressos_pkey PRIMARY KEY (id);


--
-- TOC entry 4887 (class 2606 OID 17065)
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- TOC entry 4884 (class 2606 OID 17057)
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 4850 (class 2606 OID 17091)
-- Name: lotes lotes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lotes
    ADD CONSTRAINT lotes_pkey PRIMARY KEY (id);


--
-- TOC entry 4868 (class 2606 OID 16854)
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- TOC entry 4866 (class 2606 OID 16835)
-- Name: notificacoes notificacoes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notificacoes
    ADD CONSTRAINT notificacoes_pkey PRIMARY KEY (id);


--
-- TOC entry 4860 (class 2606 OID 16808)
-- Name: pagamentos pagamentos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pagamentos
    ADD CONSTRAINT pagamentos_pkey PRIMARY KEY (id);


--
-- TOC entry 4862 (class 2606 OID 16810)
-- Name: pagamentos pagamentos_transacao_id_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pagamentos
    ADD CONSTRAINT pagamentos_transacao_id_key UNIQUE (transacao_id);


--
-- TOC entry 4870 (class 2606 OID 16872)
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- TOC entry 4836 (class 2606 OID 16678)
-- Name: perfis perfis_nome_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.perfis
    ADD CONSTRAINT perfis_nome_key UNIQUE (nome);


--
-- TOC entry 4838 (class 2606 OID 16676)
-- Name: perfis perfis_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.perfis
    ADD CONSTRAINT perfis_pkey PRIMARY KEY (id);


--
-- TOC entry 4876 (class 2606 OID 16981)
-- Name: produtores produtores_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.produtores
    ADD CONSTRAINT produtores_pkey PRIMARY KEY (id);


--
-- TOC entry 4873 (class 2606 OID 16879)
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- TOC entry 4848 (class 2606 OID 17099)
-- Name: setores setores_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.setores
    ADD CONSTRAINT setores_pkey PRIMARY KEY (id);


--
-- TOC entry 4878 (class 2606 OID 16992)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 4840 (class 2606 OID 16692)
-- Name: usuarios usuarios_cpf_cnpj_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_cpf_cnpj_key UNIQUE (cpf_cnpj);


--
-- TOC entry 4842 (class 2606 OID 16690)
-- Name: usuarios usuarios_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_email_key UNIQUE (email);


--
-- TOC entry 4844 (class 2606 OID 16688)
-- Name: usuarios usuarios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_pkey PRIMARY KEY (id);


--
-- TOC entry 4864 (class 2606 OID 16824)
-- Name: webhooks webhooks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.webhooks
    ADD CONSTRAINT webhooks_pkey PRIMARY KEY (id);


--
-- TOC entry 4885 (class 1259 OID 17058)
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- TOC entry 4871 (class 1259 OID 16881)
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- TOC entry 4874 (class 1259 OID 16880)
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- TOC entry 4895 (class 2620 OID 16843)
-- Name: eventos trigger_eventos_atualizado_em; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trigger_eventos_atualizado_em BEFORE UPDATE ON public.eventos FOR EACH ROW EXECUTE FUNCTION public.atualizar_atualizado_em();


--
-- TOC entry 4898 (class 2620 OID 16846)
-- Name: ingressos trigger_ingressos_atualizado_em; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trigger_ingressos_atualizado_em BEFORE UPDATE ON public.ingressos FOR EACH ROW EXECUTE FUNCTION public.atualizar_atualizado_em();


--
-- TOC entry 4897 (class 2620 OID 16845)
-- Name: lotes trigger_lotes_atualizado_em; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trigger_lotes_atualizado_em BEFORE UPDATE ON public.lotes FOR EACH ROW EXECUTE FUNCTION public.atualizar_atualizado_em();


--
-- TOC entry 4896 (class 2620 OID 16844)
-- Name: setores trigger_setores_atualizado_em; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trigger_setores_atualizado_em BEFORE UPDATE ON public.setores FOR EACH ROW EXECUTE FUNCTION public.atualizar_atualizado_em();


--
-- TOC entry 4894 (class 2620 OID 16842)
-- Name: usuarios trigger_usuarios_atualizado_em; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trigger_usuarios_atualizado_em BEFORE UPDATE ON public.usuarios FOR EACH ROW EXECUTE FUNCTION public.atualizar_atualizado_em();


--
-- TOC entry 4893 (class 2606 OID 16836)
-- Name: notificacoes notificacoes_usuario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notificacoes
    ADD CONSTRAINT notificacoes_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- TOC entry 4892 (class 2606 OID 16693)
-- Name: usuarios usuarios_perfil_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_perfil_id_fkey FOREIGN KEY (perfil_id) REFERENCES public.perfis(id) ON DELETE RESTRICT;


-- Completed on 2025-05-28 13:05:08

--
-- PostgreSQL database dump complete
--

