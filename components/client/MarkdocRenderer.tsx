import React from 'react';
import Markdoc from '@markdoc/markdoc';

export default function MarkdocRenderer({
  content,
}: {
  content: { node: any };
}) {
  const rendered = Markdoc.renderers.react(
    Markdoc.transform(content.node),
    React
  );
  return <>{rendered}</>;
}
