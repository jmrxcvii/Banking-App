drop database if exists banking_system;
create database banking_system;
use banking_system;
drop table if exists Bank;
drop table if exists Branch;
drop table if exists Accounts;
drop table if exists Loan;
drop table if exists Transactions;
drop table if exists Payments;
drop table if exists Staff;
drop table if exists Customer;
drop table if exists Comments;
drop table if exists Feedback;
drop table if exists Customer_Borrows;
drop table if exists Customer_Holds;
drop table if exists Staff_Borrows;
drop table if exists Staff_Holds;

create table Bank
	(ID		        integer,
	 bank_name		varchar(20),
	 primary key (ID)
	);

create table Branch
	(brchNo				 integer, 
	 street		 		 varchar(20), 
	 city	       	 varchar(20),
	 zip	         bigint,
	 b_state	     varchar(20),   
	 num_employees numeric(5,2) check (num_employees >= 0),
	 b_status	     varchar(20),
	 cash_held     numeric(8,2) check (cash_held >= 0),
	 b_name varchar(20),
	 B_ID integer,
	 primary key (brchNo),
	 foreign key (B_ID) references Bank(ID) on delete set null
	);

create table Accounts
	(accNo			  integer, 
	 acc_type			varchar(20), 
	 balance		  numeric(8,2) check (balance >= 0),
	 a_status			varchar(20),
	 Brch_ID      integer,
	 primary key (accNo),
	 foreign key (Brch_ID) references Branch(brchNo) on delete set null
	);

create table Loan
	(loanNo			   integer, 
	 loan_type		 varchar(20), 
	 balance		   numeric(8,2) check (balance >= 0),
	 l_status			 varchar(10),
	 interest_rate numeric(8,3) check (interest_rate >= 0),
	 Brch_ID integer,
	 primary key (loanNo),
	 foreign key (Brch_ID) references Branch(brchNo) on delete set null
	);

create table Transactions
	(transNo		 integer NOT NULL AUTO_INCREMENT, 
	 trans_type	 varchar(20),
	 t_date		   varchar(20), 
	 t_time		   varchar(20), 
	 amount		   numeric(8,2) check (amount >= 0),
	 Acc_ID      integer,
	 primary key (transNo),
	 foreign key (Acc_ID) references Accounts(accNo) on delete set null
	);

create table Payments
	(payNo		   integer NOT NULL AUTO_INCREMENT, 
	 p_date		   varchar(20), 
	 p_time		   varchar(20), 
	 amount		   numeric(8,2) check (amount >= 0),
	 Loan_ID     integer,
	 primary key (payNo),
	 foreign key (Loan_ID) references Loan(loanNo) on delete set null
	);

create table Staff
	(staffNo		     integer, 
	 First_name	     varchar(20),
	 Last_name		   varchar(20),  
	 street	         varchar(20),
	 city		         varchar(20),
	 zip		         bigint,  
	 s_state		     varchar(20),
	 date_of_birth	 varchar(20), 
	 phone	         varchar(20),
	 email		       varchar(20), 
	 branch		       integer, 
	 position		     varchar(20), 
	 salary		       numeric(8,2) check (salary >= 0),
	 s_password       varchar(20),
	 primary key (staffNo)
	);

create table Customer
	(custNo		       integer, 
	 First_name	     varchar(20),
	 Last_name		   varchar(20), 
	 street	         varchar(20),
	 city		         varchar(20),
	 zip		         bigint, 
	 c_state		     varchar(20), 
	 date_of_birth	 varchar(20),  
	 phone	         varchar(20),
	 email		       varchar(20),
	 c_password      varchar(20),
	 primary key (custNo)
	);

create table Comments
	(commNo		     integer NOT NULL AUTO_INCREMENT, 
	 c_message	   varchar(50),
	 threat_flag	 integer, 
	 c_date		     varchar(20), 
	 Trans_ID integer,
	 Staff_ID integer,
	 primary key (commNo),
	 foreign key (Trans_ID) references Transactions(transNo) on delete set null,
	 foreign key (Staff_ID) references Staff(staffNo) on delete set null
	);

create table Feedback
	(feedNo		    integer NOT NULL AUTO_INCREMENT,
	 f_message		varchar(50), 
	 f_date		    varchar(20), 
	 Cust_ID      integer,
	 primary key (feedNo),
	 foreign key (Cust_ID) references Customer(custNo) on delete set null
	);

create table Customer_Borrows
	(Loan_ID		integer, 
	 Cust_ID		integer, 
	 primary key (Loan_ID,Cust_ID),
	 foreign key (Loan_ID) references Loan(loanNo),
	 foreign key (Cust_ID) references Customer(custNo)
	);

create table Customer_Holds
	(Acc_ID		  integer, 
	 Cust_ID		integer, 
	 primary key (Acc_ID,Cust_ID),
	 foreign key (Acc_ID) references Accounts(accNo),
	 foreign key (Cust_ID) references Customer(custNo)
	);

create table Staff_Borrows
	(Loan_ID		integer, 
	 Staff_ID		integer, 
	 primary key (Loan_ID,Staff_ID),
	 foreign key (Loan_ID) references Loan(loanNo),
	 foreign key (Staff_ID) references Staff(staffNo)
	);

create table Staff_Holds
	(Acc_ID		  integer, 
	 Staff_ID		integer, 
	 primary key (Acc_ID,Staff_ID),
	 foreign key (Acc_ID) references Accounts(accNo),
	 foreign key (Staff_ID) references Staff(staffNo)
	);