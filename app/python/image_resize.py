from PIL import Image
from io import BytesIO
import time, base64, sys, os, json

def image_resize(base64_string):
    start_time = time.time()

    list = []
    origin_img = Image.open(BytesIO(base64.b64decode(base64_string)))
    image_dict = {"1920" : origin_img.resize((1920, 1920)), "640" : origin_img.resize((640, 640)), "320" : origin_img.resize((320, 320))}

    [list.append(convert_base64(image_dict[resolution])) for resolution in image_dict]

    end_time = time.time()
    process_time = end_time-start_time
    list.append(json.dumps({'process_second' : process_time}))

    return list

def convert_base64(image):
    try :
        image.save("tmp.jpg", 'JPEG')
        resized_image = open("tmp.jpg", 'rb').read()
        string = base64.b64encode(resized_image).decode("utf-8")
        size = image.size
        status = "0000"
        error_msg = ""
    except :
        status = "9999"
        error_msg = "fatal error, tmp.jpg file doesn't exist"

    result = json.dumps(
        {'status': status, 'img_string': string, 'image_size': str(size[0]),'error_msg': error_msg})

    return result

# open Image and convert to base64 for test
open_img = open("test1.jpg", 'rb').read()
_string = base64.b64encode(open_img).decode("utf-8")
print(image_resize(sys.argv[1]))