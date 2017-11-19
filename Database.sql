DROP DATABASE OSIP;
CREATE DATABASE OSIP; 
USE OSIP;

CREATE TABLE merged_database (
Peptide_ID VARCHAR(50) NOT NULL,
Chrm VARCHAR(10), 
strand VARCHAR(20), 
Chr_start INTEGER, 
Chr_stop INTEGER,
Peptide LONGTEXT,
Annot VARCHAR(200),
Source VARCHAR(200));
LOAD DATA LOCAL INFILE '/home/rashmi/arapeps-db/mysql_db_files/merged_database.tsv' INTO TABLE merged_database IGNORE 1 LINES;
DESCRIBE merged_database;

CREATE TABLE TAR_info (
TAR_ID VARCHAR(50) NOT NULL,
source LONGTEXT, 
BC VARCHAR(20), 
BC_expression VARCHAR(100), 
BC_Chr INTEGER,
BC_Chr_start INTEGER,
BC_Chr_stop INTEGER,
PQ VARCHAR(20), 
PQ_expression VARCHAR(100), 
PQ_Chr INTEGER,
PQ_Chr_start INTEGER,
PQ_Chr_stop INTEGER,
Nucleotide LONGTEXT,
Len_TAR INTEGER);
LOAD DATA LOCAL INFILE '/home/rashmi/arapeps-db/mysql_db_files/TAR_info.tsv' INTO TABLE TAR_info IGNORE 1 LINES;
DESCRIBE TAR_info;

CREATE TABLE TAR_TilingArray_info (
TAR_ID VARCHAR(50) NOT NULL,
BC VARCHAR(20), 
BC_expression VARCHAR(100), 
BC_Chr INTEGER,
BC_Chr_start INTEGER,
BC_Chr_stop INTEGER,
PQ VARCHAR(20), 
PQ_expression VARCHAR(100), 
PQ_Chr INTEGER,
PQ_Chr_start INTEGER,
PQ_Chr_stop INTEGER,
Len_TAR INTEGER,
Nucleotide LONGTEXT);
LOAD DATA LOCAL INFILE '/home/rashmi/arapeps-db/mysql_db_files/TAR_TilingArrays.tsv' INTO TABLE TAR_TilingArray_info IGNORE 1 LINES;
DESCRIBE TAR_TilingArray_info;

CREATE TABLE TAR_RNAseq_info (
Gene_id VARCHAR(50) NOT NULL,
BC FLOAT, 
WT_BC FLOAT, 
WT_PQ FLOAT,
PQ FLOAT);
LOAD DATA LOCAL INFILE '/home/rashmi/arapeps-db/mysql_db_files/TAR_RNAseq_info.tsv' INTO TABLE TAR_RNAseq_info IGNORE 1 LINES;
DESCRIBE TAR_RNAseq_info;

CREATE TABLE RNAseq_info (
Sl_ID VARCHAR(50) NOT NULL,
TAR_ID VARCHAR(50) NOT NULL,
Chrm VARCHAR(5),
Chr_start VARCHAR(10),
Chr_stop VARCHAR(10), 
Gene_id VARCHAR(50),
Tool VARCHAR(50),
Experiment VARCHAR(255));
LOAD DATA LOCAL INFILE '/home/rashmi/arapeps-db/mysql_db_files/RNAseq_info.tsv' INTO TABLE RNAseq_info IGNORE 1 LINES;
DESCRIBE RNAseq_info;

CREATE TABLE Genes_FPKM (
Gene_id VARCHAR(50) NOT NULL,
sample_name VARCHAR(5),
fpkm FLOAT,
conf_hi FLOAT, 
conf_lo FLOAT,
quant_status VARCHAR(10), 
stdev FLOAT,
Tool VARCHAR(50),
Description VARCHAR(255));
LOAD DATA LOCAL INFILE '/home/rashmi/arapeps-db/mysql_db_files/Genes_FPKM.tsv' INTO TABLE Genes_FPKM IGNORE 1 LINES;
DESCRIBE Genes_FPKM;

CREATE TABLE Isoforms_FPKM (
Isoform_id VARCHAR(50) NOT NULL,
sample_name VARCHAR(5),
fpkm FLOAT,
conf_hi FLOAT, 
conf_lo FLOAT,
quant_status VARCHAR(10), 
stdev FLOAT);
LOAD DATA LOCAL INFILE '/home/rashmi/arapeps-db/mysql_db_files/Isoforms_FPKM.tsv' INTO TABLE Isoforms_FPKM IGNORE 1 LINES;
DESCRIBE Isoforms_FPKM;

CREATE TABLE Cuffcmp_loci (
Gene_id VARCHAR(50) NOT NULL,
Chr_pos VARCHAR(50),
TAIR10_id VARCHAR(50),
Isoform_id LONGTEXT);
LOAD DATA LOCAL INFILE '/home/rashmi/arapeps-db/mysql_db_files/cuffcmp.loci' INTO TABLE Cuffcmp_loci IGNORE 1 LINES;
DESCRIBE Cuffcmp_loci;

CREATE TABLE SIP_info (
TAR_ID VARCHAR(50) NOT NULL,
SIP_ID VARCHAR(50) NOT NULL,
Other_Annotations VARCHAR(100),
Source LONGTEXT,
Strand VARCHAR(10),
peptide_sequence VARCHAR(255),
length_of_peptide INTEGER,
Homologs VARCHAR (200),
NoOfSeqsAln VARCHAR(10),
MeanAlnScore FLOAT,
dNdS FLOAT);
LOAD DATA LOCAL INFILE '/home/rashmi/arapeps-db/mysql_db_files/SIP_info.tsv' INTO TABLE SIP_info IGNORE 1 LINES;
DESCRIBE SIP_info;

CREATE TABLE SIP_Annotations_info (
SIP_ID VARCHAR(50) NOT NULL,
Other_Annotations VARCHAR(50),
Dataset VARCHAR(50)); 
LOAD DATA LOCAL INFILE '/home/rashmi/arapeps-db/mysql_db_files/SIP_Annotations_info.tsv' INTO TABLE SIP_Annotations_info IGNORE 1 LINES;
DESCRIBE SIP_Annotations_info;

CREATE TABLE Homologs_info (
SIP_ID VARCHAR(50) NOT NULL,
Chromosome VARCHAR(50),
Identity VARCHAR(10),
Coverage VARCHAR(10),
Mismatches VARCHAR(10),
Gaps VARCHAR(10),
Query_start VARCHAR(10),
Query_end VARCHAR(10),
Subj_start VARCHAR(10),
Subj_end VARCHAR(10),
Evalue VARCHAR(10),
Score VARCHAR(10));
LOAD DATA LOCAL INFILE '/home/rashmi/arapeps-db/mysql_db_files/Homologs_info.txt' INTO TABLE Homologs_info IGNORE 1 LINES;
DESCRIBE Homologs_info;

CREATE TABLE Peptide_positions_info (
SIP_ID VARCHAR(50) NOT NULL,
peptide VARCHAR(255), 
peptide_chr VARCHAR(5),
peptide_Chr_start VARCHAR(50),
peptide_chr_end VARCHAR(50));
LOAD DATA LOCAL INFILE '/home/rashmi/arapeps-db/mysql_db_files/SIP_positions_info.txt' INTO TABLE Peptide_positions_info IGNORE 1 LINES;
DESCRIBE Peptide_positions_info;

CREATE TABLE Mapped_Annotations_info (
TAR_ref VARCHAR(50),
TAR_ID VARCHAR(50) NOT NULL,
TAIR10_annotation VARCHAR(100),
PLncDB_annotation VARCHAR(200),
PLncDB_dataset VARCHAR(200),
TU VARCHAR(50));
LOAD DATA LOCAL INFILE '/home/rashmi/arapeps-db/mysql_db_files/Mapped_Annotations_info.tsv' INTO TABLE Mapped_Annotations_info IGNORE 1 LINES;
DESCRIBE Mapped_Annotations_info;

CREATE TABLE signal_peptides_info (
ID VARCHAR(50) NOT NULL,
DScore FLOAT, 
Signal_start INTEGER,
Signal_stop INTEGER,
Prepropeptide VARCHAR(255), 
Signal_sequence VARCHAR(255), 
Propeptide VARCHAR(255),
pep_len INTEGER);
LOAD DATA LOCAL INFILE '/home/rashmi/arapeps-db/mysql_db_files/signal_peptides_info.txt' INTO TABLE signal_peptides_info IGNORE 1 LINES;
DESCRIBE signal_peptides_info;

CREATE TABLE TMdomains_info (
TM_ref VARCHAR(50) NOT NULL,
ID VARCHAR(50) NOT NULL,
Pep_seq VARCHAR(200),
Predicted_TMHs INTEGER,
Exp_numberofAAs FLOAT,
Exp_number_first60AAs FLOAT,
Total_prob FLOAT,
start1 INTEGER,
stop1 INTEGER,
TM_helix1_start INTEGER,
TM_helix1_stop INTEGER,
start2 INTEGER,
stop2 INTEGER,
TM_helix2_start INTEGER,
TM_helix2_stop INTEGER,
start3 INTEGER,
stop3 INTEGER,
TM_helix3_start INTEGER,
TM_helix3_stop INTEGER,
start4 INTEGER,
stop INTEGER);
LOAD DATA LOCAL INFILE '/home/rashmi/arapeps-db/mysql_db_files/TMdomains_info.tsv' INTO TABLE TMdomains_info IGNORE 1 LINES;
DESCRIBE TMdomains_info;

CREATE TABLE Functional_Annotations_info1 (
SIP_ID VARCHAR(50) NOT NULL,
Matching_prots LONGTEXT,
GO_Func_description VARCHAR(200),
GO_Term VARCHAR(200),
p_value VARCHAR(20));
LOAD DATA LOCAL INFILE '/home/rashmi/arapeps-db/mysql_db_files/func_annotations_BLASTP.tsv' INTO TABLE Functional_Annotations_info1 IGNORE 1 LINES;
DESCRIBE Functional_Annotations_info1;

CREATE TABLE Functional_Annotations_info2 (
SIP_ID VARCHAR(50) NOT NULL,
domain_name VARCHAR(200),
PFAM_ID VARCHAR(200),
PFAM_domain_description VARCHAR(200),
HMMER_signf VARCHAR (20),
PFAM2GO VARCHAR(200),
PFAM_GO VARCHAR(200));
LOAD DATA LOCAL INFILE '/home/rashmi/arapeps-db/mysql_db_files/func_annotations_HMMER.tsv' INTO TABLE Functional_Annotations_info2 IGNORE 1 LINES;
DESCRIBE Functional_Annotations_info2;

CREATE TABLE Functional_Annotations_sORFs (
sORF_ID VARCHAR(50) NOT NULL,
domain_name VARCHAR(200),
PFAM_ID VARCHAR(200),
HMMER_signf VARCHAR (20));
LOAD DATA LOCAL INFILE '/home/rashmi/arapeps-db/mysql_db_files/sORF_functional_Annotations_HMMER.tsv' INTO TABLE Functional_Annotations_sORFs IGNORE 1 LINES;
DESCRIBE Functional_Annotations_sORFs;

CREATE TABLE Functional_Annotations_LW (
LW_ID VARCHAR(50) NOT NULL,
domain_name VARCHAR(200),
PFAM_ID VARCHAR(200),
HMMER_signf VARCHAR (20));
LOAD DATA LOCAL INFILE '/home/rashmi/arapeps-db/mysql_db_files/LW_functional_Annotations_HMMER.tsv' INTO TABLE Functional_Annotations_LW IGNORE 1 LINES;
DESCRIBE Functional_Annotations_LW;

CREATE TABLE Cluster_Annotation (
ID VARCHAR(50) NOT NULL,
Cluster_name INTEGER);
LOAD DATA LOCAL INFILE '/home/rashmi/arapeps-db/mysql_db_files/Cluster_Annotations.tsv' INTO TABLE Cluster_Annotation IGNORE 1 LINES;
DESCRIBE Cluster_Annotation;

CREATE TABLE Clusters 
  AS (SELECT Cluster_name, GROUP_CONCAT(ID SEPARATOR ', ') AS 'peptides' FROM Cluster_Annotation GROUP BY Cluster_name);
DESCRIBE Clusters;

CREATE TABLE LeaseWalker_peptide_info (
Pep_ID VARCHAR(50) NOT NULL, 
LW_ID VARCHAR(100),
Chrm INTEGER,
Strand VARCHAR(5),
Start_TAIR6 VARCHAR(15),
Stop_TAIR6 VARCHAR(15),
Start_TAIR10 VARCHAR(15),
Stop_TAIR10 VARCHAR(15),
nucleotide LONGTEXT,
Preproprotein VARCHAR(225),
signalPeptide VARCHAR(225),
Proprotein VARCHAR(225),
dNdS FLOAT,
pep_length INTEGER);
LOAD DATA LOCAL INFILE '/home/rashmi/arapeps-db/mysql_db_files/LeaseWalker_peptide_info.tsv' INTO TABLE LeaseWalker_peptide_info IGNORE 1 LINES;
DESCRIBE LeaseWalker_peptide_info;

CREATE TABLE LW_pep_info (
LW_ID VARCHAR(50) NOT NULL,
LW_OtherAnnot VARCHAR(100), 
LW_pep LONGTEXT,
LW_pep_len INTEGER);
LOAD DATA LOCAL INFILE '/home/rashmi/arapeps-db/mysql_db_files/LW_pep.tsv' INTO TABLE LW_pep_info IGNORE 1 LINES;
DESCRIBE LW_pep_info;

CREATE TABLE LeaseWalker_tblastn_Rice (
Pep_ID VARCHAR(50) NOT NULL, 
LW_ID VARCHAR(100),
rice_chr VARCHAR(20),
significance VARCHAR(15),
HSP_start INTEGER,
HSP_end INTEGER,
Identity FLOAT);
LOAD DATA LOCAL INFILE '/home/rashmi/arapeps-db/mysql_db_files/LW_tblastn_Rice.tsv' INTO TABLE LeaseWalker_tblastn_Rice IGNORE 1 LINES;
DESCRIBE LeaseWalker_tblastn_Rice;

CREATE TABLE LeaseWalker_clusters (
Pep_ID VARCHAR(50) NOT NULL, 
LW_ID VARCHAR(100),
LW_cluster VARCHAR(50));
LOAD DATA LOCAL INFILE '/home/rashmi/arapeps-db/mysql_db_files/LW_clusters.tsv' INTO TABLE LeaseWalker_clusters IGNORE 1 LINES;
DESCRIBE LeaseWalker_clusters;

CREATE TABLE LeaseWalker_blastp_plants ( 
LW_ID VARCHAR(100) NOT NULL,
LW_Homolog VARCHAR(50),
Identity VARCHAR(10),
Coverage VARCHAR(10),
Mismatches VARCHAR(10),
Gaps VARCHAR(10),
Query_start VARCHAR(10),
Query_end VARCHAR(10),
Subj_start VARCHAR(10),
Subj_end VARCHAR(10),
Evalue VARCHAR(10),
Score VARCHAR(10));
LOAD DATA LOCAL INFILE '/home/rashmi/arapeps-db/mysql_db_files/LW_BLAST.out' INTO TABLE LeaseWalker_blastp_plants IGNORE 1 LINES;
DESCRIBE LeaseWalker_blastp_plants;

CREATE TABLE LeaseWalker_Salktiling (
Pep_ID VARCHAR(50) NOT NULL, 
LW_ID VARCHAR(100),
LW_salktiling LONGTEXT);
LOAD DATA LOCAL INFILE '/home/rashmi/arapeps-db/mysql_db_files/LW_Salktiling.tsv' INTO TABLE LeaseWalker_Salktiling IGNORE 1 LINES;
DESCRIBE LeaseWalker_Salktiling;

CREATE TABLE LeaseWalker_mpss (
Pep_ID VARCHAR(50) NOT NULL, 
LW_ID VARCHAR(100),
LW_mpss VARCHAR(250));
LOAD DATA LOCAL INFILE '/home/rashmi/arapeps-db/mysql_db_files/LW_mpss.tsv' INTO TABLE LeaseWalker_mpss IGNORE 1 LINES;
DESCRIBE LeaseWalker_mpss;

CREATE TABLE Hanada_sORF_info (
sORF_ID VARCHAR(50) NOT NULL, 
Chrm INTEGER,
strand VARCHAR(5),
chr_leftTAIR8 VARCHAR(200),
chr_rightTAIR8 VARCHAR(200),
chr_leftTAIR10 VARCHAR(200),
chr_rightTAIR10 VARCHAR(200),
Probe_ID VARCHAR(50),
No_of_expression INTEGER,
FLcDNA VARCHAR(50),
Translational_evidence CHAR(10),
TypeofHomology VARCHAR(50),
NoOFHomologs INTEGER,
genefamilyID INTEGER,
TAIR10_Annotation VARCHAR(100));
LOAD DATA LOCAL INFILE '/home/rashmi/arapeps-db/mysql_db_files/Hanada_sORF_info-xlsx.txt' INTO TABLE Hanada_sORF_info LINES TERMINATED BY '\r';
DESCRIBE Hanada_sORF_info;

CREATE TABLE sORF_nucl_info (
sORF_ID VARCHAR(50) NOT NULL, 
sORF_nucl LONGTEXT);
LOAD DATA LOCAL INFILE '/home/rashmi/arapeps-db/mysql_db_files/sORF_nucl.tsv' INTO TABLE sORF_nucl_info IGNORE 1 LINES;
DESCRIBE sORF_nucl_info;

CREATE TABLE Hanada_dNdS_info (
ID VARCHAR(50) NOT NULL, 
pos VARCHAR(100),
dNdS FLOAT,
Pval FLOAT);
LOAD DATA LOCAL INFILE '/home/rashmi/arapeps-db/mysql_db_files/Hanada_dNdS.tsv' INTO TABLE Hanada_dNdS_info IGNORE 1 LINES;
DESCRIBE Hanada_dNdS_info;

CREATE TABLE sORF_pep_info (
sORF_ID VARCHAR(50) NOT NULL,
sORF_otherAnnot VARCHAR(100), 
sORF_pep LONGTEXT,
sORF_pep_len INTEGER,
dNdS FLOAT,
Evalue FLOAT);
LOAD DATA LOCAL INFILE '/home/rashmi/arapeps-db/mysql_db_files/sORF_pep.tsv' INTO TABLE sORF_pep_info IGNORE 1 LINES;
DESCRIBE sORF_pep_info;

