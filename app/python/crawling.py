import sys
import requests, json
from bs4 import BeautifulSoup

def crawling(url):
    page = 1
    while page <= 1:
        url = url

        source_code = requests.get(url)
        plain_text = source_code.text
        soup = BeautifulSoup(plain_text, 'lxml')

        data = soup.find("section", {
            "class" : "contents product"
        })
        data_reference = data.attrs['data-reference']
        json_data = json.loads(data_reference)
        try:
            product_id = json_data['productId']
            data_vendor_item_id = data.attrs['data-vendor-item-id']
            data_item_id = data.attrs['data-item-id']
            data_sdp_style = data.find("div",{"id" : "product-contents-placeholder"}).attrs['data-sdp-style']
            result = json.dumps({'product_id':product_id, 'data_vendor_item_id': data_vendor_item_id, 'data_item_id':data_item_id, 'data_sdp_style':data_sdp_style})
        except:
            result = False
        page += 1
    return result

print(crawling(sys.argv[1]))
