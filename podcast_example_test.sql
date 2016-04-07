--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: available_times; Type: TABLE; Schema: public; Owner: raptor; Tablespace: 
--

CREATE TABLE available_times (
    id integer NOT NULL,
    uid integer,
    start_time integer,
    topic_id integer
);


ALTER TABLE available_times OWNER TO raptor;

--
-- Name: available_times_id_seq; Type: SEQUENCE; Schema: public; Owner: raptor
--

CREATE SEQUENCE available_times_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE available_times_id_seq OWNER TO raptor;

--
-- Name: available_times_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: raptor
--

ALTER SEQUENCE available_times_id_seq OWNED BY available_times.id;


--
-- Name: this_week; Type: TABLE; Schema: public; Owner: raptor; Tablespace: 
--

CREATE TABLE this_week (
    id integer
);


ALTER TABLE this_week OWNER TO raptor;

--
-- Name: timezones; Type: TABLE; Schema: public; Owner: raptor; Tablespace: 
--

CREATE TABLE timezones (
    id integer NOT NULL,
    userid integer,
    timezone integer
);


ALTER TABLE timezones OWNER TO raptor;

--
-- Name: timezones_id_seq; Type: SEQUENCE; Schema: public; Owner: raptor
--

CREATE SEQUENCE timezones_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE timezones_id_seq OWNER TO raptor;

--
-- Name: timezones_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: raptor
--

ALTER SEQUENCE timezones_id_seq OWNED BY timezones.id;


--
-- Name: topic; Type: TABLE; Schema: public; Owner: raptor; Tablespace: 
--

CREATE TABLE topic (
    id integer NOT NULL,
    name text,
    description text
);


ALTER TABLE topic OWNER TO raptor;

--
-- Name: topic_id_seq; Type: SEQUENCE; Schema: public; Owner: raptor
--

CREATE SEQUENCE topic_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE topic_id_seq OWNER TO raptor;

--
-- Name: topic_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: raptor
--

ALTER SEQUENCE topic_id_seq OWNED BY topic.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: raptor; Tablespace: 
--

CREATE TABLE users (
    id integer NOT NULL,
    usercode text,
    username text
);


ALTER TABLE users OWNER TO raptor;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: raptor
--

CREATE SEQUENCE users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE users_id_seq OWNER TO raptor;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: raptor
--

ALTER SEQUENCE users_id_seq OWNED BY users.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: raptor
--

ALTER TABLE ONLY available_times ALTER COLUMN id SET DEFAULT nextval('available_times_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: raptor
--

ALTER TABLE ONLY timezones ALTER COLUMN id SET DEFAULT nextval('timezones_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: raptor
--

ALTER TABLE ONLY topic ALTER COLUMN id SET DEFAULT nextval('topic_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: raptor
--

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);


--
-- Data for Name: available_times; Type: TABLE DATA; Schema: public; Owner: raptor
--

COPY available_times (id, uid, start_time, topic_id) FROM stdin;
28	1	131	1
29	1	132	1
30	1	133	1
31	1	134	1
32	1	135	1
33	1	136	1
34	1	137	1
35	1	138	1
36	1	139	1
37	1	140	1
38	1	141	1
39	1	142	1
40	1	158	1
41	1	159	1
42	1	160	1
43	1	161	1
44	1	162	1
45	1	163	1
46	2	124	1
47	2	126	1
48	2	128	1
49	2	131	1
50	2	137	1
51	2	138	1
52	2	139	1
53	2	140	1
54	2	144	1
55	2	145	1
56	2	146	1
\.


--
-- Name: available_times_id_seq; Type: SEQUENCE SET; Schema: public; Owner: raptor
--

SELECT pg_catalog.setval('available_times_id_seq', 56, true);


--
-- Data for Name: this_week; Type: TABLE DATA; Schema: public; Owner: raptor
--

COPY this_week (id) FROM stdin;
1
\.


--
-- Data for Name: timezones; Type: TABLE DATA; Schema: public; Owner: raptor
--

COPY timezones (id, userid, timezone) FROM stdin;
3	1	3
\.


--
-- Name: timezones_id_seq; Type: SEQUENCE SET; Schema: public; Owner: raptor
--

SELECT pg_catalog.setval('timezones_id_seq', 3, true);


--
-- Data for Name: topic; Type: TABLE DATA; Schema: public; Owner: raptor
--

COPY topic (id, name, description) FROM stdin;
1	Introduction To The Podcast	We will discuss how the podcast will take place.
\.


--
-- Name: topic_id_seq; Type: SEQUENCE SET; Schema: public; Owner: raptor
--

SELECT pg_catalog.setval('topic_id_seq', 1, true);


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: raptor
--

COPY users (id, usercode, username) FROM stdin;
1	11223344	venam
2	1122334455	test
\.


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: raptor
--

SELECT pg_catalog.setval('users_id_seq', 2, true);


--
-- Name: available_times_pkey; Type: CONSTRAINT; Schema: public; Owner: raptor; Tablespace: 
--

ALTER TABLE ONLY available_times
    ADD CONSTRAINT available_times_pkey PRIMARY KEY (id);


--
-- Name: timezones_pkey; Type: CONSTRAINT; Schema: public; Owner: raptor; Tablespace: 
--

ALTER TABLE ONLY timezones
    ADD CONSTRAINT timezones_pkey PRIMARY KEY (id);


--
-- Name: topic_name_key; Type: CONSTRAINT; Schema: public; Owner: raptor; Tablespace: 
--

ALTER TABLE ONLY topic
    ADD CONSTRAINT topic_name_key UNIQUE (name);


--
-- Name: topic_pkey; Type: CONSTRAINT; Schema: public; Owner: raptor; Tablespace: 
--

ALTER TABLE ONLY topic
    ADD CONSTRAINT topic_pkey PRIMARY KEY (id);


--
-- Name: users_pkey; Type: CONSTRAINT; Schema: public; Owner: raptor; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: users_username_key; Type: CONSTRAINT; Schema: public; Owner: raptor; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_username_key UNIQUE (username);


--
-- Name: fk_key_av_topic; Type: FK CONSTRAINT; Schema: public; Owner: raptor
--

ALTER TABLE ONLY available_times
    ADD CONSTRAINT fk_key_av_topic FOREIGN KEY (topic_id) REFERENCES topic(id);


--
-- Name: fk_key_av_uid; Type: FK CONSTRAINT; Schema: public; Owner: raptor
--

ALTER TABLE ONLY available_times
    ADD CONSTRAINT fk_key_av_uid FOREIGN KEY (uid) REFERENCES users(id);


--
-- Name: fk_key_userid; Type: FK CONSTRAINT; Schema: public; Owner: raptor
--

ALTER TABLE ONLY timezones
    ADD CONSTRAINT fk_key_userid FOREIGN KEY (userid) REFERENCES users(id);


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

