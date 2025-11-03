--
-- PostgreSQL database dump
--

\restrict NMQoC1LT63iuq0tYY1zvjpN81Tsre4mdJCaM2bfkRhpJ4YLTkwpO0ISZ4uC9O0F

-- Dumped from database version 16.10 (Debian 16.10-1.pgdg13+1)
-- Dumped by pg_dump version 16.10 (Debian 16.10-1.pgdg13+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: cache; Type: TABLE; Schema: public; Owner: gananta
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache OWNER TO gananta;

--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: gananta
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO gananta;

--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: gananta
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


ALTER TABLE public.failed_jobs OWNER TO gananta;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: gananta
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.failed_jobs_id_seq OWNER TO gananta;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gananta
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: gananta
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


ALTER TABLE public.job_batches OWNER TO gananta;

--
-- Name: jobs; Type: TABLE; Schema: public; Owner: gananta
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


ALTER TABLE public.jobs OWNER TO gananta;

--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: gananta
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jobs_id_seq OWNER TO gananta;

--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gananta
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: gananta
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO gananta;

--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: gananta
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO gananta;

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gananta
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: gananta
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO gananta;

--
-- Name: personal_access_tokens; Type: TABLE; Schema: public; Owner: gananta
--

CREATE TABLE public.personal_access_tokens (
    id bigint NOT NULL,
    tokenable_type character varying(255) NOT NULL,
    tokenable_id bigint NOT NULL,
    name text NOT NULL,
    token character varying(64) NOT NULL,
    abilities text,
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.personal_access_tokens OWNER TO gananta;

--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: gananta
--

CREATE SEQUENCE public.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.personal_access_tokens_id_seq OWNER TO gananta;

--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gananta
--

ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;


--
-- Name: queues; Type: TABLE; Schema: public; Owner: gananta
--

CREATE TABLE public.queues (
    id bigint NOT NULL,
    queue_number character varying(255) NOT NULL,
    type character varying(255) NOT NULL,
    status character varying(255) DEFAULT 'waiting'::character varying NOT NULL,
    staff_id bigint,
    called_at timestamp(0) without time zone,
    completed_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT queues_status_check CHECK (((status)::text = ANY ((ARRAY['waiting'::character varying, 'called'::character varying, 'done'::character varying])::text[]))),
    CONSTRAINT queues_type_check CHECK (((type)::text = ANY ((ARRAY['reservation'::character varying, 'walkin'::character varying])::text[])))
);


ALTER TABLE public.queues OWNER TO gananta;

--
-- Name: queues_id_seq; Type: SEQUENCE; Schema: public; Owner: gananta
--

CREATE SEQUENCE public.queues_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.queues_id_seq OWNER TO gananta;

--
-- Name: queues_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gananta
--

ALTER SEQUENCE public.queues_id_seq OWNED BY public.queues.id;


--
-- Name: services; Type: TABLE; Schema: public; Owner: gananta
--

CREATE TABLE public.services (
    id bigint NOT NULL,
    queue_id bigint NOT NULL,
    staff_id bigint NOT NULL,
    started_at timestamp(0) without time zone NOT NULL,
    finished_at timestamp(0) without time zone,
    duration_seconds integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.services OWNER TO gananta;

--
-- Name: services_id_seq; Type: SEQUENCE; Schema: public; Owner: gananta
--

CREATE SEQUENCE public.services_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.services_id_seq OWNER TO gananta;

--
-- Name: services_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gananta
--

ALTER SEQUENCE public.services_id_seq OWNED BY public.services.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: gananta
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO gananta;

--
-- Name: staff; Type: TABLE; Schema: public; Owner: gananta
--

CREATE TABLE public.staff (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    counter_number integer,
    is_active boolean DEFAULT false NOT NULL,
    current_queue_id bigint,
    total_served integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    role character varying(255) DEFAULT 'staff'::character varying NOT NULL
);


ALTER TABLE public.staff OWNER TO gananta;

--
-- Name: staff_id_seq; Type: SEQUENCE; Schema: public; Owner: gananta
--

CREATE SEQUENCE public.staff_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.staff_id_seq OWNER TO gananta;

--
-- Name: staff_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gananta
--

ALTER SEQUENCE public.staff_id_seq OWNED BY public.staff.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: gananta
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.users OWNER TO gananta;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: gananta
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO gananta;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gananta
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: gananta
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: gananta
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: gananta
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: personal_access_tokens id; Type: DEFAULT; Schema: public; Owner: gananta
--

ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);


--
-- Name: queues id; Type: DEFAULT; Schema: public; Owner: gananta
--

ALTER TABLE ONLY public.queues ALTER COLUMN id SET DEFAULT nextval('public.queues_id_seq'::regclass);


--
-- Name: services id; Type: DEFAULT; Schema: public; Owner: gananta
--

ALTER TABLE ONLY public.services ALTER COLUMN id SET DEFAULT nextval('public.services_id_seq'::regclass);


--
-- Name: staff id; Type: DEFAULT; Schema: public; Owner: gananta
--

ALTER TABLE ONLY public.staff ALTER COLUMN id SET DEFAULT nextval('public.staff_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: gananta
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: gananta
--

COPY public.cache (key, value, expiration) FROM stdin;
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: gananta
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: gananta
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: gananta
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: gananta
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: gananta
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2025_10_29_165245_create_staff_table	1
5	2025_10_29_165937_create_queues_table	1
6	2025_10_29_235455_create_personal_access_tokens_table	1
7	2025_10_29_235809_create_services_table	1
8	2025_10_30_011414_add_role_to_staff_table	1
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: gananta
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: personal_access_tokens; Type: TABLE DATA; Schema: public; Owner: gananta
--

COPY public.personal_access_tokens (id, tokenable_type, tokenable_id, name, token, abilities, last_used_at, expires_at, created_at, updated_at) FROM stdin;
1	App\\Models\\Staff	1	auth-token	a6b171c4082330e3cc61698fe26f1da173631cf9fd697e9ce14a48c74cf29bdd	["*"]	2025-10-30 22:28:08	\N	2025-10-30 22:27:12	2025-10-30 22:28:08
\.


--
-- Data for Name: queues; Type: TABLE DATA; Schema: public; Owner: gananta
--

COPY public.queues (id, queue_number, type, status, staff_id, called_at, completed_at, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: services; Type: TABLE DATA; Schema: public; Owner: gananta
--

COPY public.services (id, queue_id, staff_id, started_at, finished_at, duration_seconds, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: gananta
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
\.


--
-- Data for Name: staff; Type: TABLE DATA; Schema: public; Owner: gananta
--

COPY public.staff (id, name, email, password, counter_number, is_active, current_queue_id, total_served, created_at, updated_at, role) FROM stdin;
2	Siti Aminah	siti@counter.com	$2y$12$OSa7xZ1ike4JxhBHgBvABuXcLQbQ.1F/oix3qOQz4XkRBJuneQNvW	\N	f	\N	0	2025-10-30 22:01:29	2025-10-30 22:01:29	staff
3	Ahmad Dahlan	ahmad@counter.com	$2y$12$pD7r50XEE.lGr/fR2m17yOtjmvwng8Glt.3plmzenczGKT/QZn0Am	\N	f	\N	0	2025-10-30 22:01:29	2025-10-30 22:01:29	staff
4	Dewi Lestari	dewi@counter.com	$2y$12$kfTsATr9bL6EmyqebVION.zvAWOQLjJA/l1tnsZ.8wXxXwHYNCcNi	\N	f	\N	0	2025-10-30 22:01:29	2025-10-30 22:01:29	staff
5	Rudi Hartono	rudi@counter.com	$2y$12$SYLvMcv/fva3aYVO0KPPP.PakaPkJGeJUGC8QsLMCj7SX2.CJpqzq	\N	f	\N	0	2025-10-30 22:01:29	2025-10-30 22:01:29	staff
6	Admin User	admin@counter.com	$2y$12$rfmw2cvX3rwrz1yBr5lrE.tTMf5KUc5pEqc3QCRDAZeALpVyBC/vi	\N	t	\N	0	2025-10-30 22:01:29	2025-10-30 22:01:29	admin
1	Budi Santoso	budi@counter.com	$2y$12$iLGBucF8G.movZwKOh/Eo.SI6bV/oEg7MfOMzAiPsRsYu0/vlJHV6	1	t	\N	0	2025-10-30 22:01:29	2025-10-30 22:27:41	staff
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: gananta
--

COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) FROM stdin;
1	Test User	test@example.com	2025-10-30 21:50:04	$2y$12$Ss2q3sDqm/1BY6tZEdumCOmHY2ENUAtiKFlk7rxb3pkOZxPMuzh9C	AciE5KJ60D	2025-10-30 21:50:04	2025-10-30 21:50:04
\.


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gananta
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gananta
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gananta
--

SELECT pg_catalog.setval('public.migrations_id_seq', 8, true);


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gananta
--

SELECT pg_catalog.setval('public.personal_access_tokens_id_seq', 1, true);


--
-- Name: queues_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gananta
--

SELECT pg_catalog.setval('public.queues_id_seq', 1, false);


--
-- Name: services_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gananta
--

SELECT pg_catalog.setval('public.services_id_seq', 1, false);


--
-- Name: staff_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gananta
--

SELECT pg_catalog.setval('public.staff_id_seq', 6, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gananta
--

SELECT pg_catalog.setval('public.users_id_seq', 1, true);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: gananta
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: gananta
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: gananta
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: gananta
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: gananta
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: gananta
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: gananta
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: gananta
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: personal_access_tokens personal_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: gananta
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens personal_access_tokens_token_unique; Type: CONSTRAINT; Schema: public; Owner: gananta
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);


--
-- Name: queues queues_pkey; Type: CONSTRAINT; Schema: public; Owner: gananta
--

ALTER TABLE ONLY public.queues
    ADD CONSTRAINT queues_pkey PRIMARY KEY (id);


--
-- Name: queues queues_queue_number_unique; Type: CONSTRAINT; Schema: public; Owner: gananta
--

ALTER TABLE ONLY public.queues
    ADD CONSTRAINT queues_queue_number_unique UNIQUE (queue_number);


--
-- Name: services services_pkey; Type: CONSTRAINT; Schema: public; Owner: gananta
--

ALTER TABLE ONLY public.services
    ADD CONSTRAINT services_pkey PRIMARY KEY (id);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: gananta
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: staff staff_email_unique; Type: CONSTRAINT; Schema: public; Owner: gananta
--

ALTER TABLE ONLY public.staff
    ADD CONSTRAINT staff_email_unique UNIQUE (email);


--
-- Name: staff staff_pkey; Type: CONSTRAINT; Schema: public; Owner: gananta
--

ALTER TABLE ONLY public.staff
    ADD CONSTRAINT staff_pkey PRIMARY KEY (id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: gananta
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: gananta
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: gananta
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: personal_access_tokens_expires_at_index; Type: INDEX; Schema: public; Owner: gananta
--

CREATE INDEX personal_access_tokens_expires_at_index ON public.personal_access_tokens USING btree (expires_at);


--
-- Name: personal_access_tokens_tokenable_type_tokenable_id_index; Type: INDEX; Schema: public; Owner: gananta
--

CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);


--
-- Name: queues_status_type_created_at_index; Type: INDEX; Schema: public; Owner: gananta
--

CREATE INDEX queues_status_type_created_at_index ON public.queues USING btree (status, type, created_at);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: gananta
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: gananta
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: queues queues_staff_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: gananta
--

ALTER TABLE ONLY public.queues
    ADD CONSTRAINT queues_staff_id_foreign FOREIGN KEY (staff_id) REFERENCES public.staff(id) ON DELETE SET NULL;


--
-- Name: services services_queue_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: gananta
--

ALTER TABLE ONLY public.services
    ADD CONSTRAINT services_queue_id_foreign FOREIGN KEY (queue_id) REFERENCES public.queues(id) ON DELETE CASCADE;


--
-- Name: services services_staff_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: gananta
--

ALTER TABLE ONLY public.services
    ADD CONSTRAINT services_staff_id_foreign FOREIGN KEY (staff_id) REFERENCES public.staff(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

\unrestrict NMQoC1LT63iuq0tYY1zvjpN81Tsre4mdJCaM2bfkRhpJ4YLTkwpO0ISZ4uC9O0F

