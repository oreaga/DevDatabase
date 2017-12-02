#!/usr/bin/python

from bs4 import BeautifulSoup
import urllib
import uuid


class Crawler():

    def __init__(self):
        self.url_list = []

    def build_ted_insert(self, attrs):
        insert = 'INSERT INTO Videos (guid, runningTime, format, author, dateModified, description) VALUES (' + str(uuid.uuid4()) + ',' + attrs['length'] + ',' + attrs['format'] + ',"' + attrs['author'] + '",' + attrs['date'] + ',"' + attrs['title'] + '");'
        return insert

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
        attrs['format'] = 'mp4'
        inserts = ''

        for outer in soup.find_all('div', class_='media media--sm-v'):
            length = outer.find('span', class_='thumb__duration').text
            for div in outer.find_all('div', class_='media__message'):
                attrs['author'] = div.find('h4', class_='h12 talk-link__speaker').text.strip()
                attrs['title'] = div.find('h4', class_='h9 m5').text.strip()
                attrs['date'] = self.ted_date(div.find('span', class_='meta__val').text.strip())
                attrs['length'] = self.get_secs(length)
                insert = self.build_ted_insert(attrs)
                print insert
                inserts += insert + '\n'

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
        url_base = ['https://www.ted.com/talks?', 'sort=newest&topics%5B%5D=Technology']
        num_pages = self.get_ted_num_pages(''.join(url_base))

        for i in range(1, num_pages + 1):
            print 'Getting info for page ' + str(i)
            self.crawl_ted_page(('page=' + str(i) + '&').join(url_base))





if __name__ == '__main__':
    c = Crawler()
    c.crawl_ted_videos()
