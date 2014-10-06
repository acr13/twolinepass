import urllib2
import BeautifulSoup
#import pymysql

## Example Databse stuffs
#conn = pymysql.connect(host='localhost', user='twolinep', password='maxfl123', db='twolinep_stats')
#cur = conn.cursor()
#cur.execute("SELECT Host,User FROM user")
##for response in cur:
##    print(response)
##cur.close()
##conn.close()

def main():
    for line in urllib2.urlopen("http://www.nhl.com/ice/playerstats.htm?pg=1"):
        print line
    

main()

#def main():
#    page1 = getStatsFromPage(1)
#    print(page1)


#def getStatsFromPage(page):
#    soup = BeautifulSoup(urlopen("http://www.nhl.com/ice/playerstats.htm?pg="+str(page)).read())
#    table = soup.find('table', {'summary': '2013-2014 - Regular Season - Skater - Summary - Points'})
#    rows = table.find_all('tr')
#    # remove the first row ('results 1-30 of #####....')
#    rows.pop(1)
#
#    stats = []
#
#    for tr in rows:
#        row = []
#        cells = tr.findAll('td')
#        textRow = ""
#        
#        for td in cells:
#            textRow = textRow + td.find(text=True) + " "
#            row.append(td.find(text=True))
#            
#        stats.append(row)
#
#    stats.pop(0)
#    return stats
