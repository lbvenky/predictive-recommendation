# Predictive-recommendation

Ability to provide an Recommendation with the Medication and care plan for any given patient based on their care plan and demographics

A preliminary framework to obtain anonymous suggestions on the medications that could be prescribed based on specific criteria on the fly querying through the existing patient care record

# Requirements

If you are wanting to build and develop this, you will need the following items installed and vagrant file will do this for you. If you have the PHP setup already and don't want to have the Apache SOLR Installation then any SOLR API can be configured.

PHP 7.x<br/>
Apache<br/>
Apache SOLR

# Configuration

You will need the following constants to be defined

SOLR_API = "http://solr-url:8983/solr/collection-name/select?q=*:*"

# Configure Apache SOLR (Core and Collection)

https://lucene.apache.org/solr/guide/7_2/solr-tutorial.html#create-your-own-collection
