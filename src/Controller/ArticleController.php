<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    /**
     * @Route("/{cat_alias}/{article_alias}_{id}", name="show_article", methods={"GET"})
     */
    public function showArticle(Article $article): Response
    {
        return $this->render("article/show_article.html.twig", [
            'article' => $article
        ]);
    } # end function showArticle()

     /**
     * @Route("/voir-articles/{alias}", name="show_articles_from_category", methods={"GET"})
     */
    public function showArticlesFromCategory(Category $category, EntityManagerInterface $entityManager): Response
    {
        // NB:lorsqu'on met {alias} a la route voir-article symfony va déviner que c 'est l'alias dans la catégorie, puisque qu'on a indentiqué en premier parametre Category dans l'injection des indépendances.et lorsque nous allons appeler la route dans la nv il faudra qu'on mette l'alias et non la route.
        $articles = $entityManager->getRepository(Article::class)
            ->findBy([
                'category' => $category->getId(),
                'deletedAt' => null
            ]);

        return $this->render("article/show_articles_from_category.html.twig", [
            'articles' => $articles,
            'category' => $category
        ]);
    }

}
