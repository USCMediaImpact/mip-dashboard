from gcloud import storage
from gcloud.storage import Blob

def download(bucket_name, path, file_obj):
	client = storage.Client()
	bucket = client.get_bucket(bucket_name)
	blob = Blob(path, bucket)
	blob.download_to_file(file_obj)