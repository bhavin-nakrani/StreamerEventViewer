
## About Task

I used Laravel web application framework. I used extra bundles for implement this functionality

- Guzzlehttp
- twitch
- doctrine/dbal
- IDE helper

Bootstrap is used for Design layout. JS and CSS (Sass) are generate through webpack from resource to public access.

Auth bundle is default used and implemented Twitch Api for user login.

## Work Flow

1. Login with Twitch account
2. In Dashboard, Featured Channels list are showing through API
3. By click on "Favourite" button, Channel is store in database. (If already exist in database then you will see exist validation message)
4. In menu, "Favourite Channel" is showing stored Channel list.
5. By click on "Detail" button, you will redirect to Stream Detail page.
- First section, Streaming video and chat are display
- Second section, 6 videos are showing form selected Stream
- Third section, No Event found message because in new Twitch API, there is no officially code to get Event. I used old API code, but there is no event in all streams so simple I displayed response there.
6. Top right menu, User can logout from system

## Questions

1. How would you deploy the above on AWS? (ideally a rough architecture diagram will help)

Below services should be use for this system.
* Route53
* EC2
* RDS (optional)
* S3 (optional)

  First, Create new EC2 instance with minimum configuration (Depend on usage). Then Install Linux, Nginx, PHP, MySql.

  Set proper host directory with "var/www/html" and then get git clone of repository.

  Install all dependency of project using composer

  Install all node dependency using node install and then run "npm run production" for generate js and css from webpack

  Set proper env variable values

  Create database using RDS or in EC2 server

  Test with EC2 public IP

  After this setup zone file of domain name in Route53. Then you have to select EC2 instance endpoint.

  Wait for sometime to configure domain with instance and you will access project.

2. Where do you see bottlenecks in your proposed architecture and how would you approach scaling this app starting from 100 reqs/day to 900MM reqs/day over 6 months?

Setup Load Balancer for EC2 instance and make copy of this server. So we have 2 EC2 server under load balancer. Change endpoint to load balancer endpoint in host zone file in Route53.

Now your domain pointed with Load balancer so it will handle more requests with 2 servers. After continues monitor with CloudWatch, we can add more copy servers under Load balancer

Other way is use Redis database in ElastiCache. It's provide in memory space and very fast to get value back.
