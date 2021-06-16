<?php

declare(strict_types=1);

namespace App\Service\Formatter;

/**
 * Class GithubMessageFormatter
 * @package App\Service\Formatter
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class GithubMessageFormatter
{
    /**
     * @param array $data
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function openedIssue(array $data): string
    {
        $title = "#{$data['issue']['number']} {$data['issue']['title']}";
        $project = $data['repository']['name'];
        $sender = $data['sender']['login'];
        $date = date('d M Y H:i');

        return <<< MESSAGE
👨🏽‍🔧 Nouvelle Issue : {$project}

{$title}

Créée par {$sender}
🕒 {$date}
MESSAGE;
    }

    /**
     * @param array $data
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function closedIssue(array $data): string
    {
    }

    /**
     * @param array $data
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function assignedIssue(array $data): string
    {
        $title = "#{$data['issue']['number']} {$data['issue']['title']}";
        $assignee = $data['assignee']['login'];
        $project = $data['repository']['name'];
        $date = date('d M Y H:i');

        return <<< MESSAGE
👨🏽‍🔧 Assignation de tâche : {$project}

{$title}

👨‍💻 {$assignee}
🕒 {$date}
MESSAGE;
    }

    /**
     * @param array $issues
     * @param string $project
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function issues(array $issues, string $project): string
    {
        $data = [];
        foreach ($issues as $issue) {
            $title = "#{$issue['number']} {$issue['title']}";
            $assignee = $issue['assignee'] ? $issue['assignee']['login'] : 'bernard-ng';
            $data[] = <<< MESSAGE

🛠 **$title**
🚻 **$assignee**

MESSAGE;
        }

        $message = join(' ', $data);
        return <<< MESSAGE
Il y a encore du travail sur **{$project}**, voici un petit rappel et les tâches de chacun
fermez l'issue sur github pour signaler que vous avez fini

$message

MESSAGE;
    }

    /**
     * @param array $data
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function push(array $data): string
    {
        $commit = substr(strval($data['after']), 0, 8);
        $project = $data['repository']['name'];
        $pusher = $data['pusher']['name'];
        $message = $data['head_commit']['message'];
        $date = date('d M Y H:i');

        return <<< MESSAGE
⬆️ Push : {$project}
🗒 {$commit} : {$message}

👨‍💻 {$pusher}
🕒 {$date}
MESSAGE;
    }
}
