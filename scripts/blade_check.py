from pathlib import Path
import re
p=Path(r"d:\DASHBOARD\p-contract\resources\views\contracts\index.blade.php")
s=p.read_text(encoding='utf-8')
lines=s.splitlines()
stack=[]
errors=[]
for i,l in enumerate(lines, start=1):
    for m in re.finditer(r"@(if|elseif|else|endif|forelse|endforelse|foreach|endforeach)", l):
        tok=m.group(1)
        if tok in ('if','forelse','foreach'):
            stack.append((tok,i))
        elif tok in ('elseif','else'):
            if not stack:
                errors.append((i,'unexpected '+tok))
        elif tok in ('endif','endforelse','endforeach'):
            if not stack:
                errors.append((i,'unexpected '+tok))
            else:
                stack.pop()

print('Unclosed stack entries:', len(stack))
if stack:
    for tok,lineNo in stack:
        print(f'unclosed {tok} opened at line {lineNo}')

print('Unexpected tokens:', errors)

# print lines around last unclosed if if any
if stack:
    tok,lineNo=stack[-1]
    start=max(1,lineNo-5)
    print('\nContext around last unclosed:')
    for ln in range(start,lineNo+6):
        if ln<=len(lines):
            print(f"{ln:4}: {lines[ln-1]}")
