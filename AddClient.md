1. Prepare new client mysql table base big query
We split Client data to different mysql table. this can make every client have owner speical field.
Better keep the mysql table have same field with big query.
2. Prepare the backbground corn job debug env
we do not need rerun all the clients for debug. so can only enabled new client sync settings.
3. Prepare background cron job insert mysql sql
keep big query have same result as some exists client will make this job easy. but you can still have different result columns. just need do some changes in this insert sql to make this client can sync.
4.