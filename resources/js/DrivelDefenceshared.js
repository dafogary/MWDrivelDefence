/*    Filename: DrivelDefenceshared.js                                                                                                          */
/* Description: Plain English web page checking applications - JavaScript shared between DrivelDefenceWeb and DrivelDefenceText applications    */
/*                                                                      */
/* Version  Date:        Revised by:    Description of change:          */
/* P0.27    25/01/2006   J Rugg         Initial issue.                  */
/* 1b0      11/10/2006   J Rugg         */


function lookupItem(szBad,szGood) //constructor
{
this.szBad=szBad;
this.szGood=szGood;
}

function lut() //constructor
{
this.itemCount=0;
this.table=new Array();
this.addItem=addItem;
}

function addItem(szBad,szGood)
{
var x=new lookupItem(szBad,szGood);
this.table[this.itemCount]=x
this.itemCount++;
}

var bg=new lut; //bg=BadGood.
/* A */
bg.addItem("absence of","no, none");
bg.addItem("abundance","enough, plenty, a lot (or say how many)");
bg.addItem("the academies programme","lower case");
bg.addItem("academy","Only use upper case when referring to the name of an academy, like Mossbourne Community Academy.");
bg.addItem("academy converters","lower case");
bg.addItem("academy order","lower case");
bg.addItem("academy trust","lower case");
bg.addItem("accede to","allow, agree to");
bg.addItem("accelerate","speed up");
bg.addItem("accentuate","stress");
bg.addItem("Access to Work","Upper case when referring directly to the actual programme, otherwise use lower case.");
bg.addItem("accommodation","where you live, home");
bg.addItem("accompanying","with");
bg.addItem("accomplish","do, finish");
bg.addItem("according to our records","our records show");
bg.addItem("accordingly","in line with this, so");
bg.addItem("accountancy service provider","Upper case when referring to the business area covered by Money Laundering Regulations. Do not use the acronym.");
bg.addItem("Accounts Office","Upper case.");
bg.addItem("act","Lowercase. Only use upper case when using the full title: Planning and Compulsory Purchase Act 2004, for example.");
bg.addItem("Activation PIN","Upper case. Activation PIN has been changed to Activation Code on outgoing correspondence from the Government Gateway. Until all hard-coded instances of Activation PIN have been removed from the Online Services pages, use ‘Activation Code (also known as Activation PIN)’.");
bg.addItem("act, act of Parliament","Lower case. Only use upper case when using the full title: Planning and Compulsory Purchase Act 2004, for example.");
bg.addItem("Active voice","Use the active rather than passive voice. This will help us write concise, clear content.");
bg.addItem("acknowledge","thank you for");
bg.addItem("acquaint yourself with","find out about, read");
bg.addItem("acquiesce","agree");
bg.addItem("acquire","buy, get");
bg.addItem("additional","extra, more");
bg.addItem("adjacent","next to");
bg.addItem("adjustment","change, alteration");
bg.addItem("admissible","allowed, acceptable");
bg.addItem("advancing","GOV.UK word to avoid.");
bg.addItem("advantageous","useful, helpful");
bg.addItem("advise","tell, say (unless you are giving advice)");
bg.addItem("advisor","adviser");
bg.addItem("affix","add, write, fasten, stick on, fix to");
bg.addItem("afford an opportunity","let, allow");
bg.addItem("afforded","given");
bg.addItem("aforesaid","this, earlier in this document");
bg.addItem("agenda","GOV.UK word to avoid. Unless it's for a meeting, avoid using agenda.");
bg.addItem("aggregate","total");
bg.addItem("agile","Upper case when referring to the Agile Manifesto and principles and processes, otherwise use lower case.");
bg.addItem("A-level","no hyphen, lower case level");
bg.addItem("al-Qaeda’","al-Qa’ida");
bg.addItem("al-Qaeda","al-Qa’ida");
bg.addItem("al-Qaida’ ","al-Qa’ida");
bg.addItem("aligned","lined up, in line");
bg.addItem("alleviate","ease, reduce");
bg.addItem("allocate","divide, share, add, give");
bg.addItem("along the lines of","like, as in");
bg.addItem("alternative","choice, other");
bg.addItem("alternative provision","lower case");
bg.addItem("alternatively","or, on the other hand");
bg.addItem("ameliorate","improve, help");
bg.addItem("amendment","change");
bg.addItem("&","and - unless it's a departments logo image or a company's name as it appears on the Companies House register");
bg.addItem("animal health","lower case");
bg.addItem("anticipate","expect");
bg.addItem("anti-social","antisocial");
bg.addItem("apparent","clear, plain, obvious, seeming");
bg.addItem("applicant","you"); //(the)
bg.addItem("application","use");
bg.addItem("applied general qualifications","lower case");
bg.addItem("appreciable","large, great");
bg.addItem("apprenticeship programme","lower case");
bg.addItem("apprise","inform, tell");
bg.addItem("appropriate","proper, right, suitable");
bg.addItem("appropriate to","suitable for"); // !!! possible duplicate matches (miss out?)
bg.addItem("approximately","about, roughly");
bg.addItem("A road","A-road");
bg.addItem("Armed Forces","armed forces - lower case");
bg.addItem("arms-length body","arm’s length body - Apostrophe, no hyphen.");
bg.addItem("A star","when writing in terms of a GCSE grade this should A*");
bg.addItem("as a consequence of","because");
bg.addItem("as of the date of","from");
bg.addItem("as regards","about, on the subject of");
bg.addItem("ascertain","find out");
bg.addItem("assemble","build, gather, put together");
bg.addItem("Assembly Ministers","assembly ministers - lower case");
bg.addItem("assistance","help");
bg.addItem("at an early date","soon (or say when)");
bg.addItem("at its discretion","can, may (or edit out)");
bg.addItem("at the moment","now (or edit out)");
bg.addItem("at the present time","now (or edit out)");
bg.addItem("attempt","try");
bg.addItem("attend","come to, go to, be at");
bg.addItem("attendance allowance","Attendance Allowance - upper case");
bg.addItem("attributable to","due to, because of");
bg.addItem("authorise","allow, let");
bg.addItem("authority","right, power, may (as in 'have the authority to')");
bg.addItem("axiomatic","obvious, goes without saying");

/* B */
bg.addItem("Baseline","baseline - lower case");
bg.addItem("belated","late");
bg.addItem("beneficial","helpful, useful");
bg.addItem("bestow","give, award");
bg.addItem("blog","a blog is a site where blogs are posted, if referring to a post use blog post");
bg.addItem("breach","break");
bg.addItem("by means of","by");
bg.addItem("Board","board, unless it's part of an official title such as the Defence Executive Board, but lower case for example, the defence ICT design authority board.");
bg.addItem("btec national diploma","BTEC National Diploma - upper case");
bg.addItem("Business Continuity Management","business continuity management - lower case");
bg.addItem("Business Plan","business plan - lower case");
bg.addItem("Business Statement","business statement - lowercase");

/* C */
bg.addItem("Cabinet","cabinet - lower case");
bg.addItem("calculate","work out, decide");
bg.addItem("cease","finish, stop, end");
bg.addItem("Chair","chair - lower case");
bg.addItem("Chairman","chairman - lower case unless used in a title: Air Cdre David Rowland, Chairman");
bg.addItem("Chairwoman","chairwoman - lower case unless used in a title: Air Cdre David Rowland, Chairman");
bg.addItem("Chairperson","chairperson - lower case unless used in a title: Air Cdre David Rowland, Chairman");
bg.addItem("circumvent","get round, avoid, skirt, circle");
bg.addItem("Chemical, Biological, Radiological or Nuclear (CBRN) materials","chemical, biological, radiological or nuclear (CBRN) materials - lowercase in full, uppercase for acronym.");
bg.addItem("clarification","explanation, help");
bg.addItem("civil service","Civil Service - uppercase");
bg.addItem("Civil Servants","civil servants - use lowercase");
bg.addItem("Coalition","coalition - always lowercase unless at the start of a sentence.");
bg.addItem("Code of Practice","code of practice - lower case");
bg.addItem("collaborate","GOV.UK word to avoid. Use working with");
bg.addItem("combating","GOV.UK word to avoid.");
bg.addItem("combine","mix"); //NB possible duplicate matches.
bg.addItem("combined","together");
bg.addItem("Command Paper","command paper - lowercase");
bg.addItem("third-party software","commercial software");
bg.addItem("third party software","commercial software");
bg.addItem("COTS","commercial");
bg.addItem("commercial of the shelf","commercial");
bg.addItem("commence","start, begin");
bg.addItem("commit","GOV.UK word to avoid. We need to be more specific - we’re either doing something or we’re not");
bg.addItem("communicate","talk, write, telephone (be specific)");
bg.addItem("competent","able, can");
bg.addItem("compile","make, collect");
bg.addItem("complete","fill in, finish");
bg.addItem("completion","end");
bg.addItem("comply with","keep to, meet");
bg.addItem("component","part");
bg.addItem("comprise","make up, include");
bg.addItem("compulsory","(you) must"); //bg.addItem("(it is) compulsory","(you) must");
bg.addItem("conceal","hide");
bg.addItem("concerning","about, on");
bg.addItem("conclusion","end");
bg.addItem("concur","agree");
bg.addItem("condition","rule");
bg.addItem("Conduct of Business Rules","conduct of business rules");
bg.addItem("consequently","so");
bg.addItem("considerable","great, important");
bg.addItem("constitute","make up, form");
bg.addItem("construe","interpret");
bg.addItem("consult","talk to, meet, ask");
bg.addItem("Consultation Responses","consultation responses - lowercase");
bg.addItem("consumption","amount used");
bg.addItem("contemplate","think about");
bg.addItem("Continuous Improvement","continuous improvement");
bg.addItem("contrary to","against, despite");
bg.addItem("correct","put right");
bg.addItem("correspond","write");
bg.addItem("cooperation","co-operation");
bg.addItem("Core Standards","core standards - lowercase");
bg.addItem("costs the sum of","costs");
bg.addItem("Council","council - lower case, even in names for example Engineering council.");
bg.addItem("counter","against");
bg.addItem("courteous","polite");
bg.addItem("countering","GOV.UK word to avoid.");
bg.addItem("Critical National Infrastructure","critical national infrastructure - lower case");
bg.addItem("cumulative","added up, added together");
bg.addItem("currently","now (or edit out)");
bg.addItem("customary","usual, normal");
bg.addItem("customs duty","Customs Duty - uppercase");
bg.addItem("Cyber Bullying","cyber bullying - lowercase - two words");
bg.addItem("Cyber Bully","cyber bully - lowercase - two words.");

/* D */
bg.addItem("Data","data - lowercase.");
bg.addItem("deduct","take off, take away");
bg.addItem("deem to be","treat as");
bg.addItem("Defence","defence - lowercase unless the full Ministry of Defence title is being used.");
bg.addItem("Defence Team","defence team - lowercase.");
bg.addItem("defer","put off, delay");
bg.addItem("deficiency","lack of");
bg.addItem("delete","cross out");
bg.addItem("deliver ","GOV.UK word to avoid. Pizzas, post and services are delivered - not abstract concepts like improvements or priorities.");
bg.addItem("demonstrate","show, prove");
bg.addItem("denote","show");
bg.addItem("Department","department - lowercase unless in a title.");
bg.addItem("deploy","GOV.UK word to avoid. Unless it’s military or software.");
bg.addItem("depict","show");
bg.addItem("designate","point out, show, name");
bg.addItem("desire","wish, want");
bg.addItem("despatch","send, post");
bg.addItem("dispatch","send, post");
bg.addItem("despite the fact that","though, although");
bg.addItem("determine","decide, work out, set, end");
bg.addItem("detrimental","harmful, damaging");
bg.addItem("Devolved Administrations","devolved administrations - lowercase");
bg.addItem("difficulties","problems");
bg.addItem("diminish","lessen, reduce");
bg.addItem("disburse","pay, pay out");
bg.addItem("discharge","carry out");
bg.addItem("disclose","tell, show");
bg.addItem("disconnect","cut off, unplug");
bg.addItem("discontinue","stop, end");
bg.addItem("discrete","separate");
bg.addItem("discuss","talk about");
bg.addItem("disseminate","spread");
bg.addItem("Dispensation","dispensation - lowercase");
bg.addItem("Director","director - lowercase unless used in a title, such as Martin Elliot, Director, ISS Design.");
bg.addItem("Director General","director general - lowercase");
bg.addItem("director-general","director general - no hyphen");
bg.addItem("diologue","GOV.UK word to avoid. We speak to people.");
bg.addItem("Diploma","diploma - lowercase");
bg.addItem("disincentivise ","GOV.UK word to avoid.");
bg.addItem("documentation","papers, documents");
bg.addItem("domiciled in","living in");
bg.addItem("dominant","main");
bg.addItem("drive","GOV.UK word to avoid. You can only drive vehicles, not schemes or people");
bg.addItem("drive out","GOV.UK word to avoid. Unless it’s cattle");
bg.addItem("due to the fact of","because, as");
bg.addItem("duration","time, life");
bg.addItem("during which time","while");
bg.addItem("dwelling","home");

/* E */
bg.addItem("Earth","Upper case for the Earth, Planet Earth and Earth sciences, with lower case for ‘the’.");
bg.addItem("economical","cheap, good value");
bg.addItem("electronic binding tariff information","electronic Binding Tariff Information (eBTI) - Upper case, but note the lower case ‘e’.");
bg.addItem("eligible","allowed, qualified");
bg.addItem("elucidate","explain, make clear");
bg.addItem("e-mail","email");
bg.addItem("e mail","email");
bg.addItem("Emergency Plan","emergency plan - lowercase.");
bg.addItem("emphasise","stress");
bg.addItem("empower","allow, let");
bg.addItem("enable","allow");
bg.addItem("enclosed","inside, with");
bg.addItem("please find enclosed","I enclose"); //!!
bg.addItem("empower","GOV.UK word to avoid.");
bg.addItem("encounter","meet");
bg.addItem("endeavour","try");
bg.addItem("enquire","ask");
bg.addItem("enquiry","question");
bg.addItem("ensure","make sure");
bg.addItem("entitlement","right");
bg.addItem("envisage","expect, imagine");
bg.addItem("equivalent","equal, the same");
bg.addItem("erroneous","wrong");
bg.addItem("establish","show, find out, set up");
bg.addItem("evaluate","test, check");
bg.addItem("evince","show, prove");
bg.addItem("Euros","euros - lowercase");
bg.addItem("Euro","euro - lowercase");
bg.addItem("ex officio","because of his or her position");
bg.addItem("exceptionally","only when, in this case");
bg.addItem("excessive","too many, too much");
bg.addItem("exclude","leave out");
bg.addItem("excluding","apart from, except");
bg.addItem("exclusively","only");
bg.addItem("exempt from","free from");
bg.addItem("excel","Excel - uppercase as it is a brand name");
bg.addItem("Executive Director","executive director - lower case unless used in a title, for example Martin Elliot, Executive Director.");
bg.addItem("expedite","hurry, speed up");
bg.addItem("expeditiously","as soon as possible, quickly");
bg.addItem("expenditure","spending");
bg.addItem("expire","run out");
bg.addItem("extant","current, in force");
bg.addItem("extremity","limit");

/* F */
bg.addItem("FAQs","FAQs (frequently asked questions) - please try and avoid FAQs");
bg.addItem("fabricate","make, make up");
bg.addItem("facilitate","help, make possible");
bg.addItem("factor","reason");
bg.addItem("facilitate","GOV.UK word to avoid. Instead, say something specific about how you’re helping");
bg.addItem("failure to","if you do not");
bg.addItem("finalise","end, finish");
bg.addItem("Finance and Procurement","finance and procurement");
bg.addItem("financial penalty","fine");
bg.addItem("focusing","GOV.UK word to avoid.");
bg.addItem("following","after");
bg.addItem("for the duration of","during, while");
bg.addItem("for the purpose of","to, for");
bg.addItem("for the reason that","because");
bg.addItem("formulate","plan, devise");
bg.addItem("forthwith","now, at once");
bg.addItem("forward","send");
bg.addItem("foster","GOV.UK word to avoid. Unless it’s children or a persons name.");
bg.addItem("FOI Act","you can make a Freedom of Information (FOI) request, not a FOI Act");
bg.addItem("frequently","often");
bg.addItem("full payment submission","Full Payment Submission - uppercase");
bg.addItem("Funding Agreement","funding agreement - lowercase");
bg.addItem("furnish","give");
bg.addItem("further to","after, following");
bg.addItem("furthermore","then, also, and");

/* G */


bg.addItem("generate","produce, give, make");
bg.addItem("give consideration to","consider, think about");
bg.addItem("grant","give");
bg.addItem("Government","government - for example UK government, when in a full title such as Her Majesty’s Government of the United Kingdom of Great Britain and Northern Ireland or the Welsh Government");
bg.addItem("Government offices","government offices - lowercase");
bg.addItem("Government Procurement card","government procurement card - lowercase");
bg.addItem("Governor","governor - lowercase");
bg.addItem("Britain","use UK or United Kingdom in preference to Britain and British (UK business, UK foreign policy, ambassador and high commissioner). But British embassy, not UK embassy.");
bg.addItem("going forward","GOV.UK word to avoid. It’s unlikely we are giving travel directions");
bg.addItem("Great Britain","Refers only to England, Scotland and Wales excluding Northern Ireland. If you’re telling users about multiple areas, use (for example) ‘England, Scotland and Wales’");
bg.addItem("green deal","Green Deal - uppercase as it is a name of a programme");
bg.addItem("Green Paper","green paper - lowercase");
bg.addItem("group","Group - uppercase when using for names such as the Defence Sustainability Group.");
bg.addItem("Guidance","guidance - lowercas");

/* H */

bg.addItem("Harbour Authority","habour authority - lowercase unless a proper noun.");
bg.addItem("Harbour Master","harbour master - lowercase");
bg.addItem("Hazardous Waste Registration","hazardous waste registration - lowercase");
bg.addItem("head teacher","headteacher");
bg.addItem("henceforth","from now on, from today");
bg.addItem("hereby","now, by this (or edit out)");
bg.addItem("herein","here (or edit out)");
bg.addItem("hereinafter","after this (or edit out)");
bg.addItem("hereof","of this");
bg.addItem("hereto","to this");
bg.addItem("heretofore","until now, previously");
bg.addItem("hereunder","below");
bg.addItem("herewith","with this (or edit out)");
bg.addItem("hitherto","until now");
bg.addItem("Homepage","homepage");
bg.addItem("hold in abeyance","wait, postpone");
bg.addItem("hope and trust","hope, trust (but not both)");

/* I */
bg.addItem("if and when","if, when (but not both)");
bg.addItem("illustrate","show, explain");
bg.addItem("immediately","at once, now");
bg.addItem("impact","GOV.UK word to avoid. Do not use this as a synonym for have an effect on, or influence.");
bg.addItem("implement","carry out, do");
bg.addItem("imply","suggest, hint at");
bg.addItem("import control system","Import Control System - uppercase");
bg.addItem("in a number of cases","some (or say how many)");
bg.addItem("in accordance with","as under, in line with, because of");
bg.addItem("in addition","and, as well as, also"); //(to)
bg.addItem("in advance","before");
bg.addItem("in case of","if");
bg.addItem("in conjunction with","and, with");
bg.addItem("in connection with","for, about");
bg.addItem("in consequence","because, as a result");
bg.addItem("in excess of","more than");
bg.addItem("in lieu of","instead of");
bg.addItem("in order that","so that");
bg.addItem("in order to","GOV.UK word to avoid. Superfluous - do not use it.");
bg.addItem("in receipt of","get, have, receive");
bg.addItem("in relation to","about");
bg.addItem("in respect of","about, for");
bg.addItem("in the absence of","without");
bg.addItem("in the course of","while, during");
bg.addItem("in the event of","if");
bg.addItem("in the event that","if");
bg.addItem("in the majority of instances","most, mostly");
bg.addItem("in the near future","soon");
bg.addItem("in the neighbourhood of","about, around");
bg.addItem("in view of the fact that","as, because");
bg.addItem("inappropriate","wrong, unsuitable");
bg.addItem("inception","start, beginning");
bg.addItem("incentivise ","GOV.UK word to avoid.");
bg.addItem("Inclusion Statement","inclusion statement - lowercase");
bg.addItem("incorporating","which includes");
bg.addItem("incurred","have to pay, owe");
bg.addItem("indicate","show, suggest");
bg.addItem("inform","tell");
bg.addItem("initially","at first");
bg.addItem("initiate","GOV.UK word to avoid. Use begin, start");
bg.addItem("insert","put in");
bg.addItem("instances","cases");
bg.addItem("Instrument of Government","instrument of government - lowercase");
bg.addItem("intend to","will");
bg.addItem("intimate","say, hint");
bg.addItem("irrespective of","despite, even if");
bg.addItem("is in accordance with","agrees with, follows");
bg.addItem("is of the opinion","thinks");
bg.addItem("issue","give, send");
bg.addItem("it is known that","I/we know that");

/* J */
bg.addItem("jeopardise","risk, threaten");

/* K */

bg.addItem("Kanban","kanban - lowercase unless referring to The Kabban Method");
bg.addItem("key","GOV.UK word to avoid. Unless it unlocks something. A subject/thing is not key - it’s probably important");

/* L */
bg.addItem("land","GOV.UK word to avoid. As a verb only use if you’re talking about aircraft");
bg.addItem("large number of","many, most (or say how many)"); //(a)
bg.addItem("Law","law - always lowercase");
bg.addItem("legislative competence order","Upper case if used as the full title: the National Assembly for Wales (Legislative Competence) (Social Welfare) Order 2008. Lower case otherwise: the legislative competence orders (LCOs) are approved, rejected or withdrawn.");
bg.addItem("leverage","GOV.UK word to avoid. Unless in the financial sense");
bg.addItem("liaise with","GOV.UK word to avoid. Use, to meet with, to discuss with, to work with (whichever is more descriptive)"); //(to)
bg.addItem("local authority","local council where possible");
bg.addItem("LA","lowercase, ideally local council where possible rather than local authority.");
bg.addItem("local authority tradining standards","Local Authority Trading Standards Services - uppercase");
bg.addItem("Local Council","local council - lowercase");
bg.addItem("locality","place, area");
bg.addItem("locate","find, put");

/* M */
bg.addItem("magnitude","size");
bg.addItem("mandatory","(you) must");//(it is)
bg.addItem("manner","way");
bg.addItem("manufacture","make");
bg.addItem("marginal","small, slight");
bg.addItem("material","relevant");	//NB possible duplicate matches.
bg.addItem("materialise","happen, occur");
bg.addItem("may in the future","may, might, could");
bg.addItem("merchandise","goods");
bg.addItem("Memorandum of Understanding","memorandum of understanding - lowercase");
bg.addItem("middle east","Middle East - uppercase");
bg.addItem("Military","military - lowercase");
bg.addItem("mislay","lose");
bg.addItem("modification","change");
bg.addItem("moreover","and, also, as well");
bg.addItem("multidisciplinary","multi-disciplinary");
bg.addItem("multi disciplinary","multi-disciplinary");

/* N */
bg.addItem("NA","N/A");
bg.addItem("N A","N/A");
bg.addItem("negligible","very small");
bg.addItem("nevertheless","but, however, even so");
bg.addItem("notify","tell, let us/you know");
bg.addItem("notwithstanding","even if, despite, still, yet");
bg.addItem("nuclear decommissioning authority","Nuclear Decommissioning Authority - uppercase");
bg.addItem("numerous","many (or say how many)");

/* O */
bg.addItem("objective","aim, goal");
bg.addItem("obligatory","(you) must"); //(it is)
bg.addItem("obtain","get, receive");
bg.addItem("occasioned by","caused by, because of");
bg.addItem("on behalf of","for");
bg.addItem("on numerous occasions","often");
bg.addItem("on receipt of","when we/you get");
bg.addItem("on request","if you ask");
bg.addItem("on the grounds that","because");
bg.addItem("on the occasion that","when, if");
bg.addItem("on line","online");
bg.addItem("one stop shop","GOV.UK word to avoid. We are government, not a retail outlet");
bg.addItem("one-stop shop","GOV.UK word to avoid. We are government, not a retail outlet");
bg.addItem("Online Services","online services - lowercase");
bg.addItem("operate","work, run");
bg.addItem("optimum","best, ideal");
bg.addItem("option","choice");
bg.addItem("ordinarily","normally, usually");
bg.addItem("otherwise","or");
bg.addItem("overarching","GOV.UK word to avoid.");
bg.addItem("outstanding","unpaid");
bg.addItem("owing to","because of");

/* P */
bg.addItem("percentage of","some (or say what percentage)"); //(a)
bg.addItem("partially","partly");
bg.addItem("participate","join in, take part");
bg.addItem("particulars","details, facts");
bg.addItem("Pathfinder","pathfinder");
bg.addItem("per annum","a year");
bg.addItem("percent","per cent");
bg.addItem("perform","do");
bg.addItem("permissible","allowed");
bg.addItem("permit","let, allow");
bg.addItem("personnel","people, staff");
bg.addItem("persons","people, anyone");
bg.addItem("peruse","read, read carefully, look at");
bg.addItem("place","put");
bg.addItem("Plain English","plain English - lowercase p");
bg.addItem("planet earth","Planet Earth - uppercase");
bg.addItem("pledge","GOV.UK word to avoid. We need to be more specific - we’re either doing something or we’re not");
bg.addItem("possess","have, own");
bg.addItem("possessions","belongings");
bg.addItem("practically","almost, nearly");
bg.addItem("predominant","main");
bg.addItem("prescribe","set, fix");
bg.addItem("preserve","keep, protect");
bg.addItem("previous","earlier, before, last");
bg.addItem("principal","main");
bg.addItem("prior to","before");
bg.addItem("proceed","go ahead");
bg.addItem("procure","get, obtain, arrange");
bg.addItem("proforma","form");
bg.addItem("profusion of","plenty, too many (or say how many)");
bg.addItem("progress","GOV.UK word to avoid. Use (to) progress something - (replace with a more precise phrase saying what you are doing)"); //(to) progress something
bg.addItem("prohibit","ban, stop");
bg.addItem("projected","estimated");
bg.addItem("prolonged","long");
bg.addItem("promptly","quickly, at once");
bg.addItem("promote","GOV.UK word to avoid. Unless you’re talking about an ad campaign or some other marketing promotion");
bg.addItem("promulgate","advertise, announce");
bg.addItem("proportion","part");
bg.addItem("provide","give");
bg.addItem("provided that","if, as long as");
bg.addItem("provisions","rules, terms");
bg.addItem("proximity","closeness, nearness");
bg.addItem("purchase","buy");
bg.addItem("pursuant to","under, because of, in line with");

/* Q */
bg.addItem("qualify for","can get, be able to get");

/* R */
bg.addItem("Recovery Structures","recovery structures - lowercase");
bg.addItem("reconsider","think again about, look again at");
bg.addItem("reduce","cut");
bg.addItem("reduction","cut");
bg.addItem("referred to as","called");
bg.addItem("refer to","talk about, mention");
bg.addItem("Reform Plan","reform plan - lowercase");
bg.addItem("Regional Resilience Team","regional resilience team - lowercase");
bg.addItem("have regard to","take into account"); //()
bg.addItem("regarding","about, on");
bg.addItem("regulation","Upper case in the full title: Licensing of Animal Dealers (Scotland) Regulations 2009. (No comma before the date.) Lower case when referring to them: the licensing of animal dealers regulations.");
bg.addItem("regulations","Upper case in the full title: Licensing of Animal Dealers (Scotland) Regulations 2009. (No comma before the date.) Lower case when referring to them: the licensing of animal dealers regulations.");
bg.addItem("reimburse","repay, pay back");
bg.addItem("reiterate","repeat, restate");
bg.addItem("relating to","about");
bg.addItem("remain","stay");
bg.addItem("remainder","the rest, what is left");
bg.addItem("remittance","payment");
bg.addItem("remuneration","pay, wages, salary");
bg.addItem("render","make, give, send");
bg.addItem("represent","show, stand for, be");
bg.addItem("request","ask, question");
bg.addItem("require","need, want, force");
bg.addItem("requirements","needs, rules");
bg.addItem("reside","live");
bg.addItem("residence","home, where you live");
bg.addItem("Resilience","resilience - lowercase");
bg.addItem("Resilience Plans","resilience plans - lowercase");
bg.addItem("restriction","limit");
bg.addItem("retain","keep");
bg.addItem("review","look at (again)");
bg.addItem("revised","new, changed");
bg.addItem("ring fenced","GOV.UK word to avoid.");
bg.addItem("ring fencing","GOV.UK word to avoid.");
bg.addItem("ring-fenced","GOV.UK word to avoid.");
bg.addItem("ring-fencing","GOV.UK word to avoid.");
bg.addItem("Risk Management","risk management - lowercase");
bg.addItem("robust","GOV.UK word to avoid.");

/* S */
bg.addItem("said","the, this, that");
bg.addItem("such","the, this, that");
bg.addItem("same","the, this, that");
bg.addItem("Science and Technical Advice Cell","science and technical advice cell - lowercase");
bg.addItem("scrum","Scrum - uppercase when referring to the method for devloping products");
bg.addItem("scrutinise","read (look at) carefully");
bg.addItem("Sector Resilience Plans","sector resilience plans");
bg.addItem("select","choose");
bg.addItem("Services","services - lowercase");
bg.addItem("settle","pay");
bg.addItem("SME","GOV.UK states this acronym means small and medium-sized enterprises, if you mean subject matter expert detail it.");
bg.addItem("SMEs","GOV.UK states this acronym means small and medium-sized enterprises, if you mean subject matter expert detail it.");
bg.addItem("similarly","also, in the same way");
bg.addItem("slimming down","GOV.UK word to avoid. you don't slim down processes");
bg.addItem("solely","only");
bg.addItem("specified","given, written, set");
bg.addItem("state","say, tell us, write down");
bg.addItem("statutory","legal, by law");
bg.addItem("streamline","GOV.UK word to avoid.");
bg.addItem("strengthening","GOV.UK word to avoid. Unless it’s strengthening bridges or other structures");
bg.addItem("subject to","depending on, under, keeping to");
bg.addItem("submit","send, give");
bg.addItem("subsequent to","after");
bg.addItem("subsequent upon","after");
bg.addItem("subsequently","later");
bg.addItem("substantial","large, great, a lot of");
bg.addItem("substantially","more or less");
bg.addItem("sufficient","enough");
bg.addItem("Summary of Consultation Responses","summary of consultation responses - lower case");
bg.addItem("supplement","go with, add to");
bg.addItem("supplementary","extra, more");
bg.addItem("supply","give, sell, delivery");

/* T */
bg.addItem("tackling","GOV.UK word to avoid. Unless it’s rugby, football or some other sport");
bg.addItem("Team","team");//(the)
bg.addItem("Teamwork","teamwork - lowercase");//(the)
bg.addItem("team work","teamwork");//(the)
bg.addItem("Technical Levels","technical levels - lowercase");//(the)
bg.addItem("Tech Levels","tech levels - lowercase");//(the)
bg.addItem("tenant","you");//(the)
bg.addItem("terminate","stop, end");
bg.addItem("that being the case","if so");
bg.addItem("the question as to whether","whether");
bg.addItem("thereafter","then, afterwards");
bg.addItem("thereby","by that, because of that");
bg.addItem("therein","in that, there");
bg.addItem("thereof","of that");
bg.addItem("thereto","to that");
bg.addItem("thus","so, therefore");
bg.addItem("to date","so far, up to now");
bg.addItem("to the extent that","if, when");
bg.addItem("trading standards","Trading Standards - uppercase");
bg.addItem("Training Schools","training schools - lowercase");
bg.addItem("transfer","change, move");
bg.addItem("transforming","GOV.UK word to avoid. What are you actually doing to change it?");
bg.addItem("transmit","send");

/* U */
bg.addItem("ultimately","in the end, finally");
bg.addItem("unavailability","lack of");
bg.addItem("undernoted","the following");
bg.addItem("undersigned","I, we");
bg.addItem("undertake","agree, promise, do");
bg.addItem("HM Government","UK government");
bg.addItem("HMG","UK government");
bg.addItem("uniform","same, similar");
bg.addItem("unilateral","one-sided, one-way");
bg.addItem("unoccupied","empty");
bg.addItem("until such time","until");
bg.addItem("utilisation","use");
bg.addItem("utilise","GOV.UK word to avoid. Use use");
bg.addItem("University Technical College","university technical college - lowercase");

/* V */
bg.addItem("variation","change");
bg.addItem("virtually","almost (or edit out)");
bg.addItem("visualise","see, predict");

/* W */
bg.addItem("ways and means","ways");
bg.addItem("we have pleasure in","we are glad to");
bg.addItem("web chat","webchat");
bg.addItem("web page","webpage");
bg.addItem("web-chat","webchat");
bg.addItem("web-page","webpage");
bg.addItem("whatsoever","whatever, what, any");
bg.addItem("whensoever","when");
bg.addItem("whereas","but");
bg.addItem("whether or not","whether");
bg.addItem("WiFi","wifi - lowercase");
bg.addItem("Wi Fi","wifi - lowercase");
bg.addItem("Wi-Fi","wifi - lowercase");
bg.addItem("with a view to","to, so that");
bg.addItem("with effect from","from");
bg.addItem("with reference to","about");
bg.addItem("with regard to","about, for");
bg.addItem("with respect to","about, for");
bg.addItem("with the minimum of delay","quickly (or say when)");

/* Y */
bg.addItem("you are requested","please");
bg.addItem("your attention is drawn","please see, please note");

/* Z */
bg.addItem("zone","area, region");

/* Words and phrases to avoid */
bg.addItem("a total of","(edit out?)");
bg.addItem("absolutely","(edit out?)");
bg.addItem("abundantly","(edit out?)");
bg.addItem("actually","(edit out?)");
bg.addItem("all things being equal","(edit out?)");
bg.addItem("as a matter of fact","(edit out?)");
bg.addItem("as far as I am concerned","(edit out?)");
bg.addItem("at the end of the day","(edit out?)");
bg.addItem("at this moment in time","(edit out?)");
bg.addItem("basically","(edit out?)");
bg.addItem("current","(edit out?)");
bg.addItem("currently","(edit out?)");
bg.addItem("during the period from","(edit out?)");
bg.addItem("each and every one","(edit out?)");
bg.addItem("existing","(edit out?)");
bg.addItem("extremely","(edit out?)");
bg.addItem("I am of the opinion that","(edit out?)");
bg.addItem("I would like to say","(edit out?)");
bg.addItem("I would like to take this opportunity to","(edit out?)");
bg.addItem("in due course","(edit out?)");
bg.addItem("in the end","(edit out?)");
bg.addItem("in the final analysis","(edit out?)");
bg.addItem("in this connection","(edit out?)");
bg.addItem("in total","(edit out?)");
bg.addItem("in view of the fact that","(edit out?)");
bg.addItem("it should be understood","(edit out?)");
bg.addItem("last but not least","(edit out?)");
bg.addItem("obviously","(edit out?)");
bg.addItem("of course","(edit out?)");
bg.addItem("other things being equal","(edit out?)");
bg.addItem("quite","(edit out?)");
bg.addItem("really","(edit out?)");
bg.addItem("really quite","(edit out?)");
bg.addItem("regarding the (noun), it was","(edit out?)"); // !! tricky
bg.addItem("the fact of the matter is","(edit out?)");
bg.addItem("the month of","(edit out?)");
bg.addItem("the months of","(edit out?)");
bg.addItem("to all intents and purposes","(edit out?)");
bg.addItem("to oneÕs own mind","(edit out?)");
bg.addItem("very","(edit out?)");
bg.addItem(";","(try and avoid semi-colons)");


//alert("Item count=" + bg.itemCount);alert(bg.table[384].szBad + "->" + bg.table[384].szGood);


function stripNonPrintables(sz) // Cleans up string so that later searches of it are not thrown out by non-printable characters.
{
var len,i;
var szNew="";

len=sz.length;
for(i=0;i<len;i++)
  {
  if(sz.charCodeAt(i)<32 || (sz.charCodeAt(i)>127 && sz.charCodeAt(i)!=8216 && sz.charCodeAt(i)!=8217 && sz.charCodeAt(i)!=8220 && sz.charCodeAt(i)!=8221 && sz.charCodeAt(i)!=8364 && sz.charCodeAt(i)!=163) )  // &lsquo;, &rsquo; &ldquo; &rdquo; &euro; and &pound; symbols respectively.
    {
    //sz=sz.substring(0,i) + " " + sz.substring(i+1,len);
    //alert(sz.charAt(i) + " " + sz.charCodeAt(i));
    if(szNew.charAt(szNew.length-1)!=" ")szNew=szNew + " ";  //Only add a space character if not one there already.
    }
  else if(sz.charAt(i)==" " && szNew.charAt(szNew.length-1)==" ");  //Don't add redundant spaces.
  else if(sz.charAt(i)=="<")szNew=szNew + "&lt;";  //Any tag brackets in the string will have come from the text of the page, not from any tags. Need to convert back to &; format.
  else if(sz.charAt(i)==">")szNew=szNew + "&gt;"; 
  else if(sz.charCodeAt(i)==8216)szNew=szNew + "&lsquo;";
  else if(sz.charCodeAt(i)==8217)szNew=szNew + "&rsquo;";
  else if(sz.charCodeAt(i)==8220)szNew=szNew + "&ldquo;";
  else if(sz.charCodeAt(i)==8221)szNew=szNew + "&rdquo;";  
  else if(sz.charCodeAt(i)==8364)szNew=szNew + "&euro;";  
  else if(sz.charCodeAt(i)==163)szNew=szNew + "&pound;";

  else if(sz.charAt(i)=="&")szNew=szNew + "&amp;"; 
  else szNew=szNew + sz.charAt(i);
  }

return szNew;
}

function isCapital(c)
{
if(c>='A' && c<='Z') return true;
else return false;
}

function isSentenceEnd(sz, n) // examines the text in string sz, around the full stop at position n.
{
var bAns=true;
var len;

len=sz.length;

if (n<len) // we're not off the end of the string..yet (some tests below may be...first test condition(s) in own brackets is the check on this.)
  {
  if(sz.charAt(n)!="." && sz.charAt(n)!="!" && sz.charAt(n)!="?" && sz.charAt(n)!="\'" && sz.charAt(n)!="\"" && sz.charAt(n)!=";")bAns=false; // ';' as quotes could end in &rsquo; etc. and be a sentence end.
  else
    {
	if((n+1<len) && sz.charAt(n+1)!=" ")bAns=false;	
    //if((n+2<len) && (sz.charAt(n+1)!=" ")  && (sz.charAt(n+2)!=" ") && (sz.charAt(n+1)!="\'")  && (sz.charAt(n+1)!="\"") ) bAns=false; // ! must be followed by a space to be valid. 
   // else if((n+1<len) && sz.charAt(n)=="?" && sz.charAt(n+1)!=" ")bAns=false; // ? must be followed by a space to be valid.
//if(sz.charCodeAt(n)==8217)alert("8217");
    else if((n>0) && (sz.charAt(n)=="\'" || sz.charAt(n)=="\"") && sz.charAt(n-1)!="." &&  sz.charAt(n-1)!="!" &&  sz.charAt(n-1)!="?") bAns=false;
	else if(sz.charAt(n)==";")  //Check for full stop etc before &; type closing quote characters..
	  {
      if(!((sz.substring(n-6,n+1)=="&rsquo;" || sz.substring(n-6,n+1)=="&rdquo;") && (sz.charAt(n-7)=="." || sz.charAt(n-7)=="!" ||  sz.charAt(n-7)=="?"))) bAns=false;
	  }
    else if((n+2<len) && sz.substring(n-2,n+2)=="... " &&  !isCapital(sz.charAt(n+2)))bAns=false;  // the end of a series of ..... but not followed by a capital letter so not a sentence end.

    else if((n+1<len) && (sz.charAt(n+1)=="." || sz.charAt(n+1)=="!" || sz.charAt(n+1)=="?")) bAns=false;   // one of a series of ..... or ....! or ....?, only the last one marks the end of the sentence.

    else if( ( (n+4<len) && (sz.substring(n-2,n+4).toLowerCase()==" i.e. ") )         //One will always expect ie to be followed by text in the same sentence, so no need to check for following capital letter.
      ||     ( (n+2<len) && (sz.substring(n-4,n+2).toLowerCase()==" i.e. ") )
      ||     ( (n+2<len) && (sz.substring(n-3,n+2).toLowerCase()==" ie. ") )
	  ||     ( (n+4<len) && (sz.substring(n-2,n+4).toLowerCase()=="(i.e. ") )          //Check for brackets....
      ||     ( (n+2<len) && (sz.substring(n-4,n+2).toLowerCase()=="(i.e. ") )
      ||     ( (n+2<len) && (sz.substring(n-3,n+2).toLowerCase()=="(ie. ") ))
      {
      bAns=false;
      //alert(sz.substring(n-1,n+3) + " " + bAns);
      }

    else if( ( (n+4<len) && (sz.substring(n-2,n+4).toLowerCase()==" e.g. ") )       //One will always expect eg to be followed by text in the same sentence, so no need to check for following capital letter.
      ||     ( (n+2<len) && (sz.substring(n-4,n+2).toLowerCase()==" e.g. ") )
      ||     ( (n+2<len) && (sz.substring(n-3,n+2).toLowerCase()==" eg. ") )
	  ||     ( (n+4<len) && (sz.substring(n-2,n+4).toLowerCase()=="(e.g. ") )        //Check for brackets....
      ||     ( (n+2<len) && (sz.substring(n-4,n+2).toLowerCase()=="(e.g. ") )
      ||     ( (n+2<len) && (sz.substring(n-3,n+2).toLowerCase()=="(eg. ") ))
      {
      bAns=false;
      //alert(sz.substring(n-1,n+3) + " " + bAns);
      }

   else if( ( (n+4<len) && (sz.substring(n-2,n+4).toLowerCase()==" n.b. ") )       //One will always expect nb to be followed by text in the same sentence, so no need to check for following capital letter.
      ||     ( (n+2<len) && (sz.substring(n-4,n+2).toLowerCase()==" n.b. ") )
      ||     ( (n+2<len) && (sz.substring(n-3,n+2).toLowerCase()==" nb. ") )
	  ||     ( (n+4<len) && (sz.substring(n-2,n+4).toLowerCase()=="(n.b. ") )       //Check for brackets....
      ||     ( (n+2<len) && (sz.substring(n-4,n+2).toLowerCase()=="(n.b. ") )
      ||     ( (n+2<len) && (sz.substring(n-3,n+2).toLowerCase()=="(nb. ") ))
      {
      bAns=false;
      //alert(sz.substring(n-1,n+3) + " " + bAns);
      }

    else if((n+2<len) && sz.substring(n-4,n+1)==" etc." && !isCapital(sz.charAt(n+2)) )bAns=false;  //Not a sentence end unless followed by a capital letter.

    else if((n+2<len) && sz.substring(n-3,n+1)==" pm." && !isCapital(sz.charAt(n+2)) )bAns=false;
    else if((n+2<len) && sz.substring(n-3,n+1)==" am." && !isCapital(sz.charAt(n+2)) )bAns=false;
    else if((n+2<len) && sz.substring(n-3,n+1)==" St." && !isCapital(sz.charAt(n+2)) )bAns=false;
    else if((n+2<len) && sz.substring(n-3,n+1)==" Rd." && !isCapital(sz.charAt(n+2)) )bAns=false;
    else if((n+2<len) && sz.substring(n-3,n+1)==" Co." && !isCapital(sz.charAt(n+2)) )bAns=false;
    else if((n+1<len) && sz.charAt(n+1)>="0" && sz.charAt(n+1)<="9")bAns=false; // It's a decimal point not a full stop.
    }
  }
return bAns;
}


function getWordCount(sz)
{
//alert(sz);

var i, len, iCnt=0;
var bChar=false, bCharOld=false;
var c;

len=sz.length;
for(i=0;i<len;i++)
  {
  c=sz.charAt(i);//alert(c);//sz.substring(i,i+1);
  //if(c=="." || c=="!" || c=="?" || c==":" || c=="," || c==";" || c=="(" || c==")" || c=="/" || c=="\\" || c=="'"); //Do nothing
  if( (c>="a" && c<="z") || (c>="A" && c<="Z") || (c>="0" && c<="9") ||( c=="-") ||( c=="+") || (c=="&") ||(c=="%"))bChar=true;
  else if(c==" ")bChar=false;
  else ; // do nothing as other characters will be punctuation etc that should not affect bChar state.
  //alert(c+" " + bChar);
  if( (bChar==true) && (bCharOld==false) )iCnt++;
  bCharOld=bChar;
  }

return iCnt;
// return 0;
}


function getSentences(aSentences, sz) //pass in an array variable - an object so passed by reference and not a copy...
{
var i=0, iBegin=0, len;
var szAlert="";

len=sz.length;

for(i=0;i<len;i++)
  {
  if(isSentenceEnd(sz,i))
    {
    if (sz.substring(iBegin,i+1).indexOf("IGNORETHISSENTENCE!!")==-1) //Check we don't need to ignore this sentence
      {
      aSentences[aSentences.length]=sz.substring(iBegin,i+1); 
      }
    iBegin=i+1;
    }
  }
szAlert=len + " characters extracted from page for analysis.\n" + aSentences.length + " sentences found.";
if(iBegin<sz.length) // The extracted text from the page does not end with a complete sentence , some text is left over...
  {
  if(getWordCount(sz.substring(iBegin))>0)
    {
	szAlert=szAlert + "\n\nWARNING: extracted text does not end in a complete sentence. It ends with the following sentence fragment, which will not be included in the analysis:\n" + sz.substring(iBegin);
	if(szAlert.length>300)szAlert=szAlert.substr(0,296) + "..."; // Truncate any long fragments.
	}
  }
alert(szAlert);  
return aSentences.length;
}


function wrapAll(szOriginal, szFind, szPre, szPost)   //Wraps each find with szPre and szPost, ideal for style wrapping a fragment of text.
{
//alert(szOriginal +", " + szFind+", " +  szPre+", " +  szPost);
var szFinal="";
var matches=new Array();
var i=0,j=0;
//alert("here");
while(j<szOriginal.length)
  {
  j=szOriginal.toLowerCase().indexOf(szFind.toLowerCase(),j);//alert(j);
  if(j==-1) break;
  else matches[i++]=j;
  j++; //start next search after last match position
  }
//alert("length=" + matches.length + " szFinal=" + szFinal);
for (i=0;i<matches.length;i++) 
  {
  if(i==0)szFinal=szOriginal.substring(0,matches[0]) + szPre + szOriginal.substring(matches[0],matches[0]+szFind.length) +szPost; //alert("i==0 " + szFinal);}
  else szFinal=szFinal + szOriginal.substring(matches[i-1]+szFind.length,matches[i]) + szPre + szOriginal.substring(matches[i],matches[i]+szFind.length) +szPost; //alert("i=="+i +" " +szFinal);}
  }
szFinal=szFinal + szOriginal.substring(matches[i-1]+szFind.length);//alert("Final= " +szFinal);
return szFinal;
}

function countMatches(szOriginal, szFind) 
{
//var szFinal="";
//var matches=new Array();
var i=0,j=0;
//alert("here");
while(j<szOriginal.length)
  {
  j=szOriginal.toLowerCase().indexOf(szFind.toLowerCase(),j);//alert(j);
  if(j==-1) break;
  else i++;
  j++; //start next search after last match position
  }
return i;
}


function getRepHead(szRepTitle)
{
/*return("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">"
  + "\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">"
  + "\n<head>"
  + "\n<title>" + szRepTitle + "</title>"
  + "\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />"
  + "\n<style type=\"text/css\" media=\"screen\">"
  + "\n<!--"
  + "\nbody{font-family: arial, sans-serif; font-size:1em}"
  + "\nstrong{color: red;}"
  + "\n.footer{font-size:0.8em;}"
  + "\n.wc{color: purple; font-style: italic;}" // word count.
  + "\n#printForm{background-color: #ffebcd; padding:0.5em}" // blanched almond background.
  + "\n-->"
  + "\n</style>"

  + "\n<style type=\"text/css\" media=\"print\">"
  + "\n<!--"
  + "\nbody{font-family: arial, sans-serif; font-size:1em;}"
  + "\nstrong{color: red;}"
  + "\n.footer{font-size:0.8em;}"
  + "\n.wc{color: purple; font-style: italic;}" // word count
  + "\n#printForm{display:none}"
  + "\n-->"
  + "\n</style>"
  + "\n</head>"
  + "\n<body>");
*/

return("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">"
  + "\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">"
  + "\n<head>"
  + "\n<title>" + szRepTitle + "</title>"
  + "\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />"
  + "\n<style type=\"text/css\" media=\"all\">"
  + "\n<!--"  
  + "\nstrong{color: red;}"
  + "\n.footer{font-size:0.8em;}"
  + "\n.wc{color: purple; font-style: italic;}"
  + "\nbody"
  + "\n{"
  + "\nfont-family: Verdana, arial, sans-serif;"
  + "\nfont-size: 1em;"
  + "\ncolor: #000000;"
  + "\nmargin-top: 1em;"
  + "\nmargin-right: 0.3em;"
  + "\nmargin-bottom: 1em;"
  + "\nmargin-left: 1em;"
  + "\npadding: 0;"
  + "\nbackground-color: #003366"
  + "\n}"
  + "\n"
  + "\na:link, a:visited"
  + "\n{"
  + "\ncolor: #006699;"
  + "\ntext-decoration: none;"
  + "\nbackground-color: inherit"
  + "\n}"
  + "\n"
  + "\na:hover{"
  + "\ncolor: #ff9900;"
  + "\nbackground-color: inherit;"
  + "\ntext-decoration: underline"
  + "\n}"
  + "\n"
  + "\nh1{"
  + "\nfont: bold 1.44em arial, sans-serif;"
  + "\ncolor: #334d55;"
  + "\nmargin: 0;"
  + "\npadding: 0;"
  + "\nbackground-color: inherit"
  + "\n}"
  + "\n"
  + "\nh2{"
  + "\nfont: bold 1.2em arial,sans-serif;"
  + "\ncolor: #003366;"
  + "\nbackground-color: inherit;"
  + "\npadding: 0"
  + "\n}"
  + "\n"
  + "\nlabel{"
  + "\nfont: normal 0.9em Verdana,sans-serif;"
  + "\ncolor: #000000;"
  + "\n"
  + "\nbackground-color: inherit"
  + "\n}"
  + "\n"			
  + "\ninput{"
  + "\nfont: normal 0.9em Verdana,sans-serif"
  + "\n}"
  + "\n"
  + "\n-->"
  + "\n</style>"
  + "\n"
  + "\n<style type=\"text/css\" media=\"screen\">"
  + "\n<!--"
  + "\n#printForm{padding:0.5em; text-align:center; float:right}"
  + "\n-->"
  + "\n</style>"
  + "\n"
  + "\n<style type=\"text/css\" media=\"print\">"
  + "\n<!--"
  + "\n#printForm{display:none}"
  + "\n-->"
  + "\n</style>"
  + "\n</head>"
  + "\n<body>");

}



function getPrintForm()
{
/*return("<form id='printForm' action='javascript:void(0);'>"
 + "\n <input type='button' value='Print' onclick='window.print();' />"
 + "\n<p class='footer'><em>To save a computer file copy of this report, right click (PC) or ctrl-click (Mac) in this window and select the 'View page source' option from the popup menu, then choose 'Save file as' from the browser menu bar. Save the file with a .htm extension.</em></p>"
 + "\n</form>");
*/
return("<form id='printForm' action='javascript:void(0);'>"
 + "\n <input type='button' value='Print' onclick='window.print();' />"
 + "\n</form>");
}


function getSummaryAndDetail(aSentences, szAll) // Pass in the array of sentences, and the unexpurgated total of page text extracted...
{
var szRepDetail="", szRepSummary="", szAlts, szWarnFrag="", szInsert="";
var i, j, iSentenceWordCnt=0, iDocWordCnt=0, iSentenceCnt=0, iAltCnt=0, iOver20Cnt=0, x, iMaxCnt=0, iMaxQty=0; iWarnCnt=0;
var bMatchAZ=false;

szRepDetail = "\n<hr style='width:100%; height:2px' />";
szRepDetail = szRepDetail + "\n<h2>Details</h2>";
for(i=0;i<aSentences.length;i++)
  {
 
  iSentenceWordCnt=getWordCount(aSentences[i]);
  if(iSentenceWordCnt>0)
    {
	szRep="<br /><span class='wc'>[" + iSentenceWordCnt + "]</span> " + aSentences[i];
	if(iSentenceWordCnt==iMaxCnt) iMaxQty++;  //Could be more than one sentence with max no. of words.
	else if(iSentenceWordCnt>iMaxCnt)  //Keep track of the longest sentence...
	  {
	  iMaxCnt=iSentenceWordCnt;
	  iMaxQty=1;
	  //iMaxCntUndex=i;
	  }
	}
  else continue;
  
  if(iSentenceWordCnt>=20)iOver20Cnt++;

  bMatchAZ=false;
  szAlts="";
  if(document.getElementById("chkAtoZ").checked==true)  // Check for A to Z substitutions...
    {   
    for(j=0;j<bg.itemCount;j++)
      {
      if(x=countMatches(szRep, " " + bg.table[j].szBad)) //Prefix word with space to prevent mid word faulty matches.
        {
        iAltCnt=iAltCnt+x; 
        szRep=wrapAll(szRep, " " + bg.table[j].szBad, "<strong>", "</strong>");
        szAlts=szAlts + "\n<li>Potential alternative ";
        if(x>1) szAlts=szAlts +" [" + x + " occurrences]";
        szAlts=szAlts + ": " + bg.table[j].szBad + " &rarr; " + bg.table[j].szGood + "</li>";
        bMatchAZ=true;
        }
      }
    if(bMatchAZ) szAlts="<ul>" + szAlts + "</ul>";
    } 


  
  
  if( ( (document.getElementById("myForm").rdoSentences[1].checked && iSentenceWordCnt>=20) || !document.getElementById("myForm").rdoSentences[1].checked) || bMatchAZ ) szRepDetail=szRepDetail + "\n" + (szRep + szAlts);

  if(iSentenceWordCnt>0) 
    { 
    iDocWordCnt=iDocWordCnt+iSentenceWordCnt;
    iSentenceCnt++;
    }
  }


szRepSummary="<hr style='width:100%; height:2px' />";
szRepSummary=szRepSummary + "\n<h2>Summary</h2>";

if(aSentences.length<=0)
  {
  szRepSummary=szRepSummary + "\nNo sentences detected. This may be because no sentence terminators such as '! ', '? ', or '. ' (note trailing space character), were found. Text content extracted is as follows:";
  szRepSummary=szRepSummary + "\n<p>" + szAll + "</p>";
  }
else
  {
  szRepSummary=szRepSummary + "\n<ul>";
  szRepSummary=szRepSummary + "\n<li>Total sentences with 20 or more words= " + iOver20Cnt + ".</li>";
  szRepSummary=szRepSummary + "\n<li>Longest sentence has " + iMaxCnt + " words"; 
  if(iMaxQty>1)szRepSummary=szRepSummary + " (" +iMaxQty + " sentences)";
  szRepSummary=szRepSummary + ".</li>";
  
  
  iStart=0;iWarnCnt=0;

  while( (i=szRepDetail.indexOf("<br /><span class='wc'>[" + iMaxCnt + "]</span>", iStart))!=-1)  // Mark up all sentences of maximum length...
    {
	iWarnCnt++;  
	if(iMaxQty==1)szWarnFrag="";
	else szWarnFrag= "("+iWarnCnt + " of " + iMaxQty +")";
	szInsert="<br /><strong style='color:blue; font-size:larger'>******* &darr; Your Longest Sentence " + szWarnFrag + " &darr; *******</strong>";
	szRepDetail=szRepDetail.substring(0,i-1) + szInsert + szRepDetail.substring(i,szRepDetail.length);
	iStart=i+1+szInsert.length;
	}
												  
  if(document.getElementById("chkAtoZ").checked==true)szRepSummary=szRepSummary + "\n<li>Total potential alternative words= " +  iAltCnt + ".</li>";
  szRepSummary=szRepSummary + "\n<li>Total words=" + iDocWordCnt + ".</li>";
  szRepSummary=szRepSummary + "\n<li>Total sentences=" + iSentenceCnt + ".</li>";
  szRepSummary=szRepSummary + "\n</ul>";
  szRepSummary=szRepSummary + "\n<p class='wc'>[Average sentence length = " + (iDocWordCnt/iSentenceCnt).toFixed(2) + " words].</p>";
  }

return (szRepSummary + szRepDetail);
}
