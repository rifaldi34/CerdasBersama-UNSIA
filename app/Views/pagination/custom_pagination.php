<?php $pager->setSurroundCount(6) ?>

<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center"> <!-- Added Bootstrap class -->
    <?php if ($pager->hasPrevious()) : ?>
        <li class="page-item"> <!-- Added Bootstrap class -->
            <a class="page-link" href="<?= $pager->getFirst() ?>" aria-label="<?= lang('Pager.first') ?>">
                <span aria-hidden="true"><?= htmlspecialchars('|<<') ?></span>
            </a>
        </li>
        <li class="page-item"> <!-- Added Bootstrap class -->
            <a class="page-link" href="<?= $pager->getPrevious() ?>" aria-label="<?= lang('Pager.previous') ?>">
                <span aria-hidden="true"><?= htmlspecialchars('<<') ?></span>
            </a>
        </li>
    <?php endif ?>

    <?php foreach ($pager->links() as $link): ?>
        <li class="page-item <?= $link['active'] ? 'active' : '' ?>"> <!-- Added Bootstrap class -->
            <a class="page-link" href="<?= $link['uri'] ?>">
                <?= $link['title'] ?>
            </a>
        </li>
    <?php endforeach ?>

    <?php if ($pager->hasNext()) : ?>
        <li class="page-item"> <!-- Added Bootstrap class -->
            <a class="page-link" href="<?= $pager->getNext() ?>" aria-label="<?= lang('Pager.next') ?>">
                <span aria-hidden="true"><?= htmlspecialchars('>>') ?></span>
            </a>
        </li>
        <li class="page-item"> <!-- Added Bootstrap class -->
            <a class="page-link" href="<?= $pager->getLast() ?>" aria-label="<?= lang('Pager.last') ?>">
                <span aria-hidden="true"><?= htmlspecialchars('>>|') ?></span>
            </a>
        </li>
    <?php endif ?>
    </ul>
</nav>
