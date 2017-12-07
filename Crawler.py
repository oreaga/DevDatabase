#!/usr/bin/python

from bs4 import BeautifulSoup
import urllib
import uuid
import datetime
import re
import sys
import os
import getpass


def build_insert(attrs, type):
    if type == 'video':
        insert = 'INSERT INTO Videos (guid, runningTime, format, author, dateModified, description, url) VALUES ("' + attrs.get('guid', 'null') + '",' + attrs.get('length', 'null') + ',"' + attrs.get('format', 'null') + '","' + attrs.get('author', 'null') + '",' + attrs.get('date', 'null') + ',"' + attrs.get('description', 'null') + '","' + attrs.get('url', 'null') + '");'
    elif type == 'image':
        insert = 'INSERT INTO Images (guid, imageWidth, imageHeight, format, author, dateModified, description, url) VALUES ("' + attrs.get('guid', 'null') + '",' + attrs.get('imageWidth', 'null') + ',' + attrs.get('imageHeight', 'null') + ',"' + attrs.get('format', 'null') + '","' + attrs.get('author', 'null') + '",' + attrs.get('date', 'null') + ',"' + attrs.get('description', 'null') + '","' + attrs.get('url', 'null') + '");'
    elif type == 'document':
        insert = 'INSERT INTO Documents (guid, docFormat, author, dateModified, description, url) VALUES ("' + attrs.get('guid', 'null') + '",' + '"' + attrs.get('format', 'null') + '","' + attrs.get('author', 'null') + '",' + attrs.get('date', 'null') + ',"' + attrs.get('description', 'null') + '","' + attrs.get('url', 'null') + '");'
    elif type == 'audio':
        insert = 'INSERT INTO Audio (guid, runningTime, format, author, dateModified, description, url) VALUES ("' + attrs.get('guid', 'null') + '",' + attrs.get('length', 'null') + ',"' + attrs.get('format', 'null') + '","' + attrs.get('author', 'null') + '",' + attrs.get('date', 'null') + ',"' + attrs.get('description', 'null') + '","' + attrs.get('url', 'null') + '");'
    elif type == 'parentchild':
        insert = 'INSERT INTO ParentChild (pid, cid) VALUES ("' + attrs.get('pid', 'null') + '","' + attrs.get('cid', 'null') + '");'
    elif type == 'parenttitle':
        insert = 'INSERT INTO ParentTitle (pid, title) VALUES ("' + attrs.get('pid', 'null') + '","' + attrs.get('title', 'null') + '");'
    elif type == 'childtype':
        insert = 'INSERT INTO ChildType (cid, type) VALUES ("' + attrs.get('cid', 'null') + '","' + attrs.get('type', 'null') + '");'

    return insert


class TedCrawler:

    def __init__(self):
        self.url_list = []

    def get_secs(self, length):
        if 'h' in length:
            fields = length.split('h')
            return str(60*60*int(fields[0]) + 60*int(fields[1].strip().split('m')[0]))
        else:
            fields = length.split(':')
            return str(60*int(fields[0]) + int(fields[1]))

    def ted_date(self, date_str):
            months = {'Jan': '01', 'Feb': '02', 'Mar': '03', 'Apr': '04', 'May': '05',
                      'Jun': '06', 'Jul': '07', 'Aug': '08', 'Sep': '09', 'Oct': '10',
                      'Nov': '11', 'Dec': '12'}
            fields = date_str.split(' ')
            return fields[1] + months[fields[0]] + '01'

    def crawl_ted_page(self, url):
        html = urllib.urlopen(url).read()
        soup = BeautifulSoup(html, 'lxml')
        attrs = {}
        ted_url = 'https://www.ted.com'
        inserts = ''

        # Creates inserts for videos and images on a ted page
        for outer in soup.find_all('div', class_='media media--sm-v'):
            length = outer.find('span', class_='thumb__duration').text
            attrs['format'] = 'mp4'
            for div in outer.find_all('div', class_='media__message'):
                attrs['author'] = div.find('h4', class_='h12 talk-link__speaker').text.strip()
                attrs['description'] = div.find('h4', class_='h9 m5').text.strip()
                attrs['date'] = self.ted_date(div.find('span', class_='meta__val').text.strip())
                attrs['length'] = self.get_secs(length)
                attrs['url'] = ted_url + div.find('a', class_=' ga-link')['href']
                attrs['guid'] = str(uuid.uuid4())
                video_insert = build_insert(attrs, 'video')
                inserts += video_insert + '\n'

            for img in outer.find_all('img', class_='thumb__image'):
                attrs['url'] = img['src']
                fields = attrs['url'].split('.')
                attrs['format'] = fields[-1].split('?')[0]
                res = fields[-2].split('_')[1]
                attrs['imageWidth'] = res.split('x')[0]
                attrs['imageHeight'] = res.split('x')[1]
                attrs['guid'] = str(uuid.uuid4())
                image_insert = build_insert(attrs, 'image')
                inserts += image_insert + '\n'

        with open('ted_inserts.sql', 'a+') as fl:
            fl.write(inserts.encode('utf8') + '\n')




    def get_ted_num_pages(self, url):
        text = urllib.urlopen(url).read()
        soup = BeautifulSoup(text, 'lxml')
        page_nums = []

        for page_num in soup.find_all('a', class_='pagination__item pagination__link'):
            page_nums.append(page_num.text)
        return int(page_nums[-1])

    def crawl_ted_videos(self):
        url_base = ['https://www.ted.com/talks/?', 'sort=newest&topics%5B%5D=Technology']
        num_pages = self.get_ted_num_pages(''.join(url_base))

        for i in range(1, num_pages + 1):
            print 'Getting info for page ' + str(i)
            self.crawl_ted_page(('page=' + str(i) + '&').join(url_base))


class TechCrunchCrawler:

    def __init__(self):
        self.base_url = 'https://techcrunch.com/'

    def crawl_tech(self):
        text = urllib.urlopen(self.base_url).read()
        soup = BeautifulSoup(text, 'lxml')
        attrs = {}
        inserts = ''

        # Build parent insert
        attrs['url'] = self.base_url
        attrs['description'] = 'TechCrunch homepage'
        attrs['date'] = datetime.date.today().strftime('%Y%m%d')
        attrs['format'] = 'html'
        attrs['author'] = 'TechCrunch'
        base_guid = str(uuid.uuid4())
        attrs['guid'] = base_guid
        attrs['pid'] = base_guid
        attrs['title'] = 'TechCrunch Pages'
        attrs['type'] = 'documents'
        inserts += build_insert(attrs, 'parenttitle') + '\n'
        inserts += build_insert(attrs, 'document') + '\n'

        for link in soup.find_all('a'):
            try:
                url = link['href']
                fields = url.split('/')
                if 'techcrunch.com' in url and re.match('\d{4}', fields[3]):
                    text = urllib.urlopen(url).read()
                    soup1 = BeautifulSoup(text, 'lxml')
                    attrs['url'] = url
                    attrs['date'] = fields[3] + fields[4] + fields[5]
                    attrs['description'] = link.text
                    if attrs['description'] != '' and 'Read'.encode('utf8') not in attrs['description']:
                        attrs['guid'] = str(uuid.uuid4())
                        attrs['cid'] = attrs['guid']
                        for a in soup1.find_all('a', rel='author'):
                            attrs['author'] = a.text
                        inserts += build_insert(attrs, 'document') + '\n'
                        inserts += build_insert(attrs, 'parentchild') + '\n'
                        inserts += build_insert(attrs, 'childtype') + '\n'
            except KeyError:
                print 'Bad Link'

        with open('techcrunch_inserts.sql', 'a+') as fl:
            fl.write(inserts.encode('utf8'))


"""
class StitchCrawler:

    def __init__(self):
        self.base_url = 'https://www.stitcher.com'

    def parse_time(self, dur):
        time = 0
        fields = dur.split(' ')
        for f in fields:
            if 'm' in f:
                time += 60*int(f[0:-1])
            elif 'hs' in f:
                time += 60*60*int(f[0:-2])
        return time

    def crawl_stitch(self):
        attrs = {}
        driver = webdriver.Firefox()
        text = driver.get(self.base_url + '/stitcher-list/technology-podcasts-top-shows')
        soup = BeautifulSoup(text, 'html.parser')
        inserts = ''

        for entry in soup.find_all('tr'):
            for img in entry.find_all('img', class_='sl-feedImage'):
                attrs['guid'] = str(uuid.uuid4())
                attrs['url'] = img['src']
                attrs['format'] = attrs['url'].split('.')[-1]
                attrs['author'] = 'Stitcher'
                attrs['date'] = datetime.date.today().strftime('%Y%m%d')
                attrs['imageWidth'] = img['width']
                attrs['imageHeight'] = img['height']
                attrs['description'] = img['alt'] + ' Image'
                inserts += build_insert(attrs, 'image')

            for span in entry.find_all('span', class_='sl-showName'):
                tag = span.find('a')
                attrs['author'] = span.text
                url = self.base_url + tag['href']
                attrs['url'] = url
                soup1 = BeautifulSoup(url, 'html.parser')
                dur_string = soup1.find('span', class_='duration').text
                attrs['lengh'] = self.parse_time(dur_string)
                attrs['guid'] = str(uuid.uuid4())
                attrs['format'] = 'mp3'
                attrs['description'] = soup1.find('h2', class_='title').text
                attrs['date'] = (datetime.today() - datetime.timedelta(days=int(soup1.find('span', class_='when').text.split(' ')[-3]))).strftime('%Y%m%d')
                inserts += build_insert(attrs, 'audio')
"""

class FileCrawler:

    def __init__(self):
        self.bdir = sys.argv[2]

    def crawl_fs(self):
        attrs = {}
        inserts = ''
        video_types = ['avi', 'flv', 'mp4', 'mov']
        image_types = ['jpeg', 'jpg', 'png', 'bmp', 'gif', 'tiff']
        doc_types = ['txt', 'doc', 'docx', 'html', 'py', 'c', 'java', 'pdf', 'xml', 'json', 'h']
        audio_types = ['mp3', 'aiff', 'wma', 'dat', 'wav']
        for (dirpath, dirnames, dirfiles) in os.walk(self.bdir):
            attrs['url'] = dirpath
            attrs['pid'] = str(uuid.uuid4())
            attrs['title'] = dirpath.split('/')[-1]
            inserts += build_insert(attrs, 'parenttitle') + '\n'
            for f in dirfiles:
                print f
                attrs['url'] = os.path.join(dirpath, f)
                attrs['guid'] = str(uuid.uuid4())
                attrs['format'] = f.split('.')[-1]
                st = os.stat(os.path.join(dirpath, f))
                attrs['author'] = getpass.getuser()
                attrs['date'] = datetime.datetime.fromtimestamp(st.st_ctime).strftime('%Y%m%d')

                if attrs['format'] != '':
                    attrs['cid'] = attrs['guid']
                    if attrs['format'] in doc_types:
                        attrs['type'] = 'documents'
                        inserts += build_insert(attrs, 'document') + '\n'
                        inserts += build_insert(attrs, 'parentchild') + '\n'
                        inserts += build_insert(attrs, 'childtype') + '\n'
                    elif attrs['format'] in image_types:
                        attrs['type'] = 'images'
                        inserts += build_insert(attrs, 'image') + '\n'
                        inserts += build_insert(attrs, 'parentchild') + '\n'
                        inserts += build_insert(attrs, 'childtype') + '\n'
                    elif attrs['format'] in video_types:
                        attrs['type'] = 'videos'
                        inserts += build_insert(attrs, 'video') + '\n'
                        inserts += build_insert(attrs, 'parentchild') + '\n'
                        inserts += build_insert(attrs, 'childtype') + '\n'
                    elif attrs['format'] in audio_types:
                        attrs['type'] = 'audio'
                        inserts += build_insert(attrs, 'audio') + '\n'
                        inserts += build_insert(attrs, 'parentchild') + '\n'
                        inserts += build_insert(attrs, 'childtype') + '\n'

            for d in dirnames:
                attrs['cid'] = str(uuid.uuid4())
                attrs['type'] = 'Directory'
                inserts += build_insert(attrs, 'parentchild') + '\n'
                inserts += build_insert(attrs, 'childtype') + '\n'

        with open('local_inserts.sql', 'r+') as fl:
            fl.write(inserts)

class HTMLCrawler:

    def crawl_page(self, url, depth, guid):
        inserts = ''
        attrs = {}
        if url is None:
            return
        try:
            text = urllib.urlopen(url).read()
        except:
            print 'Cannot read link text'
            return
        if text is None or ('<!doctype html>' not in text.split('\n', 1)[0].lower()):
            print 'Not a valid html file'
            return
        try:
            soup = BeautifulSoup(text, 'lxml')
        except:
            print 'Unable to parse html'
        if guid is None:
            guid = str(uuid.uuid4())
        attrs['guid'] = guid
        attrs['pid'] = guid
        attrs['format'] = 'html'
        attrs['url'] = url
        attrs['type'] = 'documents'
        try:
            attrs['title'] = url.split('www.')[1].split('.')[0]
        except:
            print url
        title = soup.find('title')
        if title is not None:
            attrs['description'] = title.text
        inserts += build_insert(attrs, 'document') + '\n'
        if depth >= 1:
            return
        inserts += build_insert(attrs, 'parenttitle') + '\n'


        for child in soup.find_all('a'):
            link = child.get('href', None)

            if link is not None:
                if len(link) > 1 and link[0] == '/':
                    child_url = url + link
                elif len(link) > 1:
                    child_url = link
                else:
                    child_url = None
                attrs['cid'] = str(uuid.uuid4())
                inserts += build_insert(attrs, 'childtype') + '\n'
                inserts += build_insert(attrs, 'parentchild') + '\n'
                self.crawl_page(child_url, depth + 1, attrs['cid'])

        with open('html_inserts.sql', 'r+') as fl:
            fl.write(inserts)


if __name__ == '__main__':
    if sys.argv[1] == 'local':
        c = FileCrawler()
        c.crawl_fs()
    elif sys.argv[1] == 'web':
        c = HTMLCrawler()
        c.crawl_page(sys.argv[2], 0, None)
    else:
        c = TedCrawler()
        c.crawl_ted_videos()
        c = TechCrunchCrawler()
        c.crawl_tech()